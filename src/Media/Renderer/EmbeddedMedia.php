<?php

namespace Mason\Media\Renderer;

use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\ResolverInterface;
use Mason\Media\EmbeddedFileRenderer\EmbeddedAudioRenderer;
use Mason\Media\EmbeddedFileRenderer\EmbeddedThumbnailRenderer;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\Renderer\Fallback;
use Omeka\Media\Renderer\RendererInterface;

class EmbeddedMedia implements RendererInterface
{

    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $audio_types = ['mp3','audio','audio/mp3','audio/mpeg'];

        $data = $media->mediaData();
        $mediaType = $data['o:media_type'];
        if (in_array($mediaType, $audio_types)){
            $renderer = new EmbeddedAudioRenderer();
        } elseif ($mediaType == "image") {
            $renderer = new EmbeddedThumbnailRenderer();
        } else {
            $renderer = new Fallback();
        }
        return $renderer->render( $view,  $media, ['link'=>'original', 'thumbnailType' => 'original']);

    }
}

