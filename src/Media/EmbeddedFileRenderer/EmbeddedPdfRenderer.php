<?php

namespace Mason\Media\EmbeddedFileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class EmbeddedPdfRenderer implements \Omeka\Media\Renderer\RendererInterface
{

    /**
     * @inheritDoc
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        return sprintf(
            '<iframe src="%s" style="width: 100%%; height: 600px;" allowfullscreen></iframe>',
            $view->escapeHtml($media->mediaData()['url']));
    }
}