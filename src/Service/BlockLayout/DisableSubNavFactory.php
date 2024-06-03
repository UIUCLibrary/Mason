<?php

namespace Mason\Service\BlockLayout;

use Mason\Site\BlockLayout\DisableSubNav;
use Psr\Container\ContainerInterface;

class DisableSubNavFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get('Omeka\EntityManager');
        $moduleManager = $container->get('Omeka\ModuleManager');
        return new DisableSubNav($entityManager, $moduleManager);
    }
}