<?php

namespace App\Tenant;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class TenantRequestListener
{
    public function __construct(
        private TenantResolver $resolver,
        private TenantProvider $provider,
        private TenantContext $context
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $slug = $this->resolver->resolveTenantSlug($request);
        if (!$slug) {
            return;
        }

        $tenant = $this->provider->getTenantBySlug($slug);
        if (!$tenant) {
            throw new \RuntimeException("Unknown tenant: $slug");
        }

        $this->context->setTenant($tenant);
    }
}
