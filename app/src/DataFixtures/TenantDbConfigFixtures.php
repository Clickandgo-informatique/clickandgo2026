<?php

namespace App\DataFixtures;

use App\Entity\Main\TenantDbConfig;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TenantDbConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Tenant principal
        $main = new TenantDbConfig();
        $main->setSlug('main');
        $main->setDbName('main_db');
        $main->setDbHost('postgres');
        $main->setDbPort(5432);
        $main->setDbUser('postgres');
        $main->setDbPassword('postgres');
        $manager->persist($main);

        // Tenant 1
        $tenant1 = new TenantDbConfig();
        $tenant1->setSlug('tenant1');
        $tenant1->setDbName('tenant1_db');
        $tenant1->setDbHost('postgres');
        $tenant1->setDbPort(5432);
        $tenant1->setDbUser('postgres');
        $tenant1->setDbPassword('postgres');
        $manager->persist($tenant1);

        // Tenant 2
        $tenant2 = new TenantDbConfig();
        $tenant2->setSlug('tenant2');
        $tenant2->setDbName('tenant2_db');
        $tenant2->setDbHost('postgres');
        $tenant2->setDbPort(5432);
        $tenant2->setDbUser('postgres');
        $tenant2->setDbPassword('postgres');
        $manager->persist($tenant2);

        $manager->flush();
    }
}
