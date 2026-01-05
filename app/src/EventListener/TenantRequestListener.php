<?php

namespace App\EventListener;

use App\Tenant\PathTenantResolver;
use App\Tenant\TenantConfigProvider;
use Hakam\MultiTenancyBundle\Event\SwitchDbEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 100)]
class TenantRequestListener
{
    public function __construct(
        private readonly PathTenantResolver $resolver,
        private readonly TenantConfigProvider $provider,
        private readonly EventDispatcherInterface $dispatcher
    ) {}

    public function __invoke(RequestEvent $event): void
    {
      
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $tenant = $this->resolver->resolveTenant($request);

        // 1️⃣ Tenant vide → STOP
        if (!$tenant || trim($tenant) === '') {
            return;
        }

        // 2️⃣ Tenant inexistant → STOP
        if (!$this->provider->tenantExists($tenant)) {
            return;
        }

        // 3️⃣ Tenant valide → switch
        $this->dispatcher->dispatch(new SwitchDbEvent($tenant));
    }
}
