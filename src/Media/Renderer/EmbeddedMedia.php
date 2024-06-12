<?php

namespace Mason\Media\Renderer;

use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\ResolverInterface;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\Renderer\RendererInterface;

class EmbeddedMedia implements RendererInterface
{

    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $data = $media->mediaData();
        $url= $data['o:source'];
        $html = <<<'HTML'
        <audio src="%1$s?disposition=inline" type="audio/mpeg" controls="">
        </audio>
        HTML;

        return sprintf(
            $html,
            $url
        );
    }
}

