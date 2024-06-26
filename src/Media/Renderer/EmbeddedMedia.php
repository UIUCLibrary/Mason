<?php

namespace Mason\Media\Renderer;

use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\ResolverInterface;
use Mason\Media\EmbeddedFileRenderer\EmbeddedAudioRenderer;
use Mason\Media\EmbeddedFileRenderer\EmbeddedPdfRenderer;
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
        $mediaType = $media->mediaType();
        if (in_array($media->mediaType(), $audio_types)){
            $renderer = new EmbeddedAudioRenderer();
        } elseif ($mediaType == "application/pdf") {
            $renderer = new EmbeddedPdfRenderer();
        } else {
            $renderer = new Fallback();
        }
        return $renderer->render( $view,  $media, ['link'=>'original', 'thumbnailType' => 'original']);

    }
}

