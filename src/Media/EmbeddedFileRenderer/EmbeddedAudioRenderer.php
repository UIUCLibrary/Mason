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
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = []): string
    {

        $data = $media->mediaData();
        $base_url = $data['manifest_uri'];
        $streaming_url = $base_url .'/object?disposition=inline';

        $attrs[] = sprintf('src="%s"', $view->escapeHtml($streaming_url));

        if (isset($options['autoplay']) && $options['autoplay']) {
            $attrs[] = 'autoplay';
        }
        if (isset($options['controls']) && $options['controls']) {
            $attrs[] = 'controls';
        }
        if (isset($options['loop']) && $options['loop']) {
            $attrs[] = 'loop';
        }
        if (isset($options['muted']) && $options['muted']) {
            $attrs[] = 'muted';
        }

        return sprintf(
            '<audio %s>%s</audio>',
            implode(' ', $attrs),
            $view->hyperlink('', $url)
        );
    }
}