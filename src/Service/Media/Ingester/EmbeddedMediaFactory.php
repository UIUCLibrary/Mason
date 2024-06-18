<?php
namespace Mason\Service\Media\Ingester;

use Mason\Media\Ingester\EmbeddedMedia;
use Omeka\Media\Ingester\Url;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class EmbeddedMediaFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {

        return new EmbeddedMedia(
            $services->get('Omeka\HttpClient'),
            $services->get('Omeka\File\Downloader'),
        );
    }
}

