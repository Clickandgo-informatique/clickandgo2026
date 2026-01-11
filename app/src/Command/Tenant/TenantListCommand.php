<?php

namespace App\Command\Tenant;

use App\Entity\Main\TenantDbConfig;
use App\Service\TenantLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:tenant:list',
    description: 'Liste tous les tenants enregistrés'
)]
class TenantListCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private TenantLogger $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tenants = $this->em->getRepository(TenantDbConfig::class)->findAll();

        foreach ($tenants as $tenant) {
            $output->writeln($tenant->getSlug());
        }

        // Log optionnel
        $this->logger->logSystem(null, 'tenant_list', 'Liste des tenants consultée');

        return Command::SUCCESS;
    }
}
