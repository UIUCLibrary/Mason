<?php

namespace Mason\Media\Ingester;

use Laminas\Form\Element\Select;
use Laminas\Form\Element\Url as UrlElement;
use Laminas\Http\Client as HttpClient;
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
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var Downloader
     */
    protected $downloader;

    public function __construct(HttpClient $httpClient, Downloader $downloader)
    {
        $this->httpClient = $httpClient;
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

        //parse the url entered and get the manifest url
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
        $manifest_matches = [];
        preg_match('/(https:\/\/digital.library.illinois.edu\/binaries\/.*)\/object.*/', $data['o:source'],$manifest_matches);
        if (!count($manifest_matches) > 1){
            $errorStore->addError('o:source', 'Invalid media URL. Expected pattern looks like https://digital.library.illinois.edu/binaries/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx-x/object');
            return;
        }
        $manifest_uri_string = $manifest_matches[1];

        //get the manifest

        $manifest_uri = new HttpUri($manifest_uri_string);
        if (!($manifest_uri->isValid() && $manifest_uri->isAbsolute())) {
            $errorStore->addError('o:source', "Couldn't extract manifest uri from user input. Expected uri input looks like https://digital.library.illinois.edu/binaries/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx-x/object?disposition=inline");
            return false;
        }

        $client = $this->httpClient;
        $client->reset();
        $client->setUri($manifest_uri);
        $response = $client->send();
        if (!$response->isOk()) {
            $errorStore->addError('o:source', sprintf(
                "Error reading %s: %s (%s)",
                $this->getLabel(),
                $response->getReasonPhrase(),
                $response->getStatusCode()
            ));
            return false;
        }

        //parse the manifest and get the media type
        $manifest = json_decode($response->getBody(), true);
        if (!($manifest && array_key_exists('media_type', $manifest))) {
            $errorStore->addError('o:source', sprintf("Couldn't find a media_type in the manifest for the resource at %s", $manifest_uri_string));
            return false;

        }
        $media->setMediaType($manifest['media_type']);
        $data['manifest_uri'] = $manifest_uri_string;

        $media->setData($data);

    }


    public function form(PhpRenderer $view, array $options = [])
    {
        $urlInput = new UrlElement('o:media[__index__][o:source]');
        $urlInput->setOptions([
            'label' => 'URL', // @translate
            'info' => 'A URL to the media from the Illinois Digital Library. Should look like “https://digital.library.illinois.edu/binaries/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx-x/object”', // @translate
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