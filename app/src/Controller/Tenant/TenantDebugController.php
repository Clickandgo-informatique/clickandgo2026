<?php

namespace App\Controller;

use App\Tenant\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TenantDebugController extends AbstractController
{
    #[Route('/{tenant}/debug', name: 'tenant_debug')]
    public function debug(
        string $tenant,
        TenantContext $tenantContext,
        EntityManagerInterface $em
    ): Response {
        // Récupération du tenant courant (défini par le listener)
        $currentTenant = $tenantContext->getRequiredTenant();

        // Vérification que le tenant dans l'URL correspond bien
        if ($currentTenant->getSlug() !== $tenant) {
            return new Response("Mismatch tenant: URL=$tenant, resolved=" . $currentTenant->getSlug(), 400);
        }

        // Test : exécuter une requête SQL simple sur la base tenant
        $conn = $em->getConnection();
        $result = $conn->fetchOne("SELECT current_database()");

        return new Response("
            Tenant slug : {$currentTenant->getSlug()}<br>
            Base utilisée : $result
        ");
    }
}
