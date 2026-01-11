<?php

namespace App\Command\Tenant;

use App\Entity\Main\TenantDbConfig;
use App\Service\TenantLogger;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:tenant:database:create',
    description: 'Crée la base de données d’un tenant'
)]
class TenantDatabaseCreateCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
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

        $params = [
            'dbname'   => null,
            'user'     => $tenant->getDbUser(),
            'password' => $tenant->getDbPassword(),
            'host'     => $tenant->getDbHost(),
            'port'     => $tenant->getDbPort(),
            'driver'   => 'pdo_pgsql',
        ];

        $connection = DriverManager::getConnection($params);
        $dbName = $tenant->getDbName();

        $connection->executeStatement("CREATE DATABASE \"$dbName\"");

        $output->writeln("✅ Base '$dbName' créée pour le tenant '$slug'.");

        // Log
        $this->logger->log($tenant, 'database_create', "Base $dbName créée");

        return Command::SUCCESS;
    }
}
