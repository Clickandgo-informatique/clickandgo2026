<?php

namespace App\Tenant;

use Symfony\Component\HttpFoundation\Request;

class TenantResolver
{
    /**
     * Extrait le slug tenant depuis l'URL.
     * Exemple : /client1/debug → "client1"
     */
    public function resolveTenantSlug(Request $request): ?string
    {
        $path = trim($request->getPathInfo(), '/'); // ex: "client1/debug"

        if ($path === '') {
            return null;
        }

        $parts = explode('/', $path);

        // Le premier segment est le tenant
        $slug = $parts[0];

        // On peut ajouter une validation simple
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
            return null;
        }

        return $slug;
    }
}
