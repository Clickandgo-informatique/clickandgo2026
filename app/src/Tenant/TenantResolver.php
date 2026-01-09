<?php

namespace App\Tenant;

use Symfony\Component\HttpFoundation\Request;

class TenantResolver
{
    public function resolveTenantSlug(Request $request): ?string
    {
        $path = trim($request->getPathInfo(), '/');

        if ($path === '') {
            return null;
        }

        $parts = explode('/', $path);

        return $parts[0]; // ex: "client1"
    }
}
