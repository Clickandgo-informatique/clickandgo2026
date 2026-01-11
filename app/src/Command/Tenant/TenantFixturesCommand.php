<?php

namespace App\Command\Tenant;

use App\Tenant\TenantEntityManagerProvider;
use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:tenant:fixtures',
    description: 'Charge les fixtures pour un tenant'
)]
class TenantFixturesCommand extends Command
{
    public function __construct(
        private TenantEntityManagerProvider $tenantEntityManagerProvider,
        private SymfonyFixturesLoader $fixturesLoader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('tenant', InputArgument::REQUIRED, 'Nom du tenant');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tenant = $input->getArgument('tenant');

        $output->writeln("🔧 Chargement des fixtures pour le tenant : <info>$tenant</info>");

        $em = $this->tenantEntityManagerProvider->getEntityManager($tenant);

        $fixtures = $this->fixturesLoader->getFixtures();

        if (empty($fixtures)) {
            $output->writeln("⚠️  Aucune fixture trouvée.");
            return Command::SUCCESS;
        }

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);

        $executor->purge();
        $executor->execute($fixtures);

        $output->writeln("✅ Fixtures chargées pour le tenant : <info>$tenant</info>");

        return Command::SUCCESS;
    }
}
