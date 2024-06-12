<?php

namespace Mason\Media\EmbeddedFileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\AudioRenderer;

class EmbeddedAudioRenderer extends AudioRenderer
{

    /**
     * @inheritDoc
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        // TODO: Implement render() method.
    }
}