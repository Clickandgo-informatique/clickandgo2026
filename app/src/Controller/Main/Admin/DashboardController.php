<?php

namespace App\Controller\Main\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route(path:'/main/admin/dashboard', name: 'main_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('main/admin/dashboard.html.twig');
    }
}
