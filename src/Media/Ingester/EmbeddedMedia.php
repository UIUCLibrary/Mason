<?php

namespace Mason\Media\Ingester;

use Laminas\Form\Element\Select;
use Laminas\Form\Element\Url as UrlElement;
use Laminas\Uri\Http as HttpUri;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Api\Request;
use Omeka\Entity\Media;
use Omeka\File\Downloader;
use Omeka\Media\Ingester\MutableIngesterInterface;
use Omeka\Media\Ingester\Url;
use Omeka\Stdlib\ErrorStore;
use Laminas\View\Renderer\PhpRenderer;
Class EmbeddedMedia implements MutableIngesterInterface
{
    /**
     * @var Downloader
     */
    protected $downloader;

    public function __construct(Downloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function getLabel()
    {
        return 'Illinois Digital Library';
    }

    public function getRenderer()
    {
        return 'embedded_media';
    }

    public function ingest(Media $media, Request $request, ErrorStore $errorStore)
    {

        $data = $request->getContent();
        if(!isset($data['o:source'])){
            $errorStore->addError('error', 'No media URL provided');
            return;
        }
        $url = new HttpUri($data['o:source']);
        if (!($url->isValid() && $url->isAbsolute())) {
            $errorStore->addError('o:source', 'Invalid media URL');
            return;
        }
        $media_manifest = new HttpUri();
        $media_manifest->parse($data['o:source']);
        print_r($media_manifest);

        $tempFile = $this->downloader->download($url, $errorStore);
        if (!$tempFile) {
            return;
        }
//        if ($data['o:media_type'] == 'image'){
////            $request->setContent(['ingest_url' => $data['o:source']]);
////            $ingester = new \Omeka\Media\Ingester\Url($this->downloader);
////            $ingester->ingest($media,$request, $errorStore);
//            $media->setThumbnail();
//        }
        $data['url'] = $url;
        $media->setData($data);

    }


    public function form(PhpRenderer $view, array $options = [])
    {
        $urlInput = new UrlElement('o:media[__index__][o:source]');
        $urlInput->setOptions([
            'label' => 'URL', // @translate
            'info' => 'A URL to the media from the Illinois Digital Library.', // @translate
        ]);
        $urlInput->setAttributes([
            'id' => 'media-url-embedded-url-source__index__',
            'required' => true,
        ]);
        return $view->formRow($urlInput);
    }

    public function update(Media $media, Request $request, ErrorStore $errorStore)
    {
        // TODO: Implement update() method.
    }

    public function updateForm(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        // TODO: Implement updateForm() method.
    }
}