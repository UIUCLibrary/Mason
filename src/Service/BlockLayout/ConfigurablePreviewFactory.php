<?php

namespace Mason\Service\BlockLayout;

use Mason\Site\BlockLayout\ConfigurablePreview;
use Psr\Container\ContainerInterface;

class ConfigurablePreviewFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get('Omeka\EntityManager');
        $apiManager = $container->get('Omeka\ApiManager');
        $translator = $container->get('MvcTranslator');
        return new ConfigurablePreview($entityManager, $apiManager, $translator);
    }
}