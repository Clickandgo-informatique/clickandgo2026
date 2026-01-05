<?php

namespace App\Tenant;

use Symfony\Component\HttpFoundation\Request;

class PathTenantResolver
{
    public function resolveTenant(Request $request): ?string
    {

        $path = trim($request->getPathInfo(), '/');

        if ($path === '') {
            return null;
        }

        $segments = explode('/', $path);
        // dd('tenant = ', $segments[0]);
        return $segments[0];
    }
}
