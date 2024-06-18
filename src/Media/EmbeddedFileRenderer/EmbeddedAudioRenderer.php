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

        $options = array_merge(self::DEFAULT_OPTIONS, $options);
        $data = $media->mediaData();
        $url= $data['o:source'];

        $attrs[] = sprintf('src="%s"', $view->escapeHtml($url));

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