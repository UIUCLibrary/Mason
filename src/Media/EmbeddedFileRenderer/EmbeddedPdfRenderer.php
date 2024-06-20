<?php

namespace Mason\Media\EmbeddedFileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\Renderer\RendererInterface;

class EmbeddedPdfRenderer implements RendererInterface
{

    /**
     * @inheritDoc
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $data = $media->mediaData();
        $base_url = $data['manifest_uri'];
        $streaming_url = $base_url .'/stream?content-disposition=inline';
        return sprintf(
            '<iframe src="%s" style="width: 100%%; height: 600px;" allowfullscreen></iframe>',
            $view->escapeHtml($streaming_url));
    }
}