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
        $main->setTenantKey('main');
        $main->setDatabaseName('main_db');
        $manager->persist($main);

        // Tenant 1
        $tenant1 = new TenantDbConfig();
        $tenant1->setTenantKey('tenant1');
        $tenant1->setDatabaseName('tenant1_db');
        $manager->persist($tenant1);

        // Tenant 2 (optionnel)
        $tenant2 = new TenantDbConfig();
        $tenant2->setTenantKey('tenant2');
        $tenant2->setDatabaseName('tenant2_db');
        $manager->persist($tenant2);

        $manager->flush();
    }
}
