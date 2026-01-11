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
    name: 'app:tenant:delete',
    description: 'Supprime un tenant et sa base de données'
)]
class TenantDeleteCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private TenantLogger $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('slug', InputArgument::REQUIRED, 'Slug du tenant à supprimer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $slug = $input->getArgument('slug');

        $tenant = $this->em->getRepository(TenantDbConfig::class)->findOneBy(['slug' => $slug]);

        if (!$tenant) {
            $output->writeln("❌ Tenant '$slug' introuvable.");
            return Command::FAILURE;
        }

        $dbName = $tenant->getDbName();

        $params = [
            'dbname'   => null,
            'user'     => $tenant->getDbUser(),
            'password' => $tenant->getDbPassword(),
            'host'     => $tenant->getDbHost(),
            'port'     => $tenant->getDbPort(),
            'driver'   => 'pdo_pgsql',
        ];

        $connection = DriverManager::getConnection($params);

        try {
            $connection->executeStatement("DROP DATABASE IF EXISTS \"$dbName\"");
            $output->writeln("🗑️  Base '$dbName' supprimée.");
        } catch (\Exception $e) {
            $output->writeln("⚠️ Impossible de supprimer la base '$dbName' : " . $e->getMessage());
            $this->logger->log($tenant, 'delete_error', $e->getMessage());
            return Command::FAILURE;
        }

        // Mise à jour du tenant
        $tenant->setStatus('deleted');
        $tenant->setDeletedAt(new \DateTimeImmutable());
        $tenant->setDeletedBy('system'); // ou un user si dashboard
        $this->em->flush();

        // Log
        $this->logger->log($tenant, 'delete', "Tenant supprimé et base $dbName supprimée");

        $output->writeln("✅ Tenant '$slug' marqué comme supprimé.");

        return Command::SUCCESS;
    }
}
