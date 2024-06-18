<?php

namespace Mason\Media\EmbeddedFileRenderer;

use Omeka\Api\Representation\MediaRepresentation;

class EmbeddedThumbnailRenderer extends \Omeka\Media\FileRenderer\ThumbnailRenderer
{

    protected function getLinkUrl(MediaRepresentation $media, $linkType)
    {
        switch ($linkType) {
            case 'original':
                return $media->mediaData()['o:source'];
            case 'item':
                return $media->item()->url();
            case 'media':
                return $media->url();
            default:
                throw new \InvalidArgumentException(sprintf('Invalid link type "%s"', $linkType));
        }
    }

}