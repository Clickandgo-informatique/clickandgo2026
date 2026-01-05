<?php

namespace App\Controller\Tenant;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'tenant_test')]
    public function test(EntityManagerInterface $em): Response
    {
        $db = $em->getConnection()->getDatabase();

        return new Response("Base de données active : " . $db);
    }
}
