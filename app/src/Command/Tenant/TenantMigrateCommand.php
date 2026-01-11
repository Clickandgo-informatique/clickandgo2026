<?php

namespace App\Command\Tenant;

use App\Entity\Main\TenantDbConfig;
use App\Service\TenantLogger;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:tenant:migrate',
    description: 'Exécute les migrations pour un tenant'
)]
class TenantMigrateCommand extends Command
{
    public function __construct(
        private \Doctrine\ORM\EntityManagerInterface $em,
        private TenantLogger $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('slug', InputArgument::REQUIRED, 'Slug du tenant');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $slug = $input->getArgument('slug');

        $tenant = $this->em->getRepository(TenantDbConfig::class)->findOneBy(['slug' => $slug]);

        if (!$tenant) {
            $output->writeln("❌ Tenant '$slug' introuvable.");
            return Command::FAILURE;
        }

        try {
            $connection = DriverManager::getConnection([
                'dbname'   => $tenant->getDbName(),
                'user'     => $tenant->getDbUser(),
                'password' => $tenant->getDbPassword(),
                'host'     => $tenant->getDbHost(),
                'port'     => $tenant->getDbPort(),
                'driver'   => 'pdo_pgsql',
            ]);

            $config = new PhpFile('migrations.php');
            $dependencyFactory = DependencyFactory::fromConnection(
                $config,
                new \Doctrine\Migrations\Configuration\Connection\ExistingConnection($connection)
            );

            $migrate = new MigrateCommand($dependencyFactory);
            $migrate->setApplication($this->getApplication());

            $args = new ArrayInput([
                'command' => 'migrations:migrate',
                '--no-interaction' => true,
            ]);

            $result = $migrate->run($args, $output);

            // Log
            $this->logger->log($tenant, 'migrate', "Migrations exécutées");

            return $result;

        } catch (\Exception $e) {
            $this->logger->log($tenant, 'migrate_error', $e->getMessage());
            $output->writeln("❌ Erreur migration : " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
