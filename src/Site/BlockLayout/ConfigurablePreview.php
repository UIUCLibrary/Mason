<?php

namespace Mason\Site\BlockLayout;

use Doctrine\ORM\EntityManager;
use Laminas\Form\Form;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Form\Element\PropertySelect;
use Omeka\Form\Element as OmekaElement;
use Laminas\Form\Element;
use Omeka\Site\BlockLayout\TemplateableBlockLayoutInterface;
use Omeka\Api\Manager as ApiManager;
use Omeka\Site\BlockLayout\BrowsePreview;

class ConfigurablePreview extends BrowsePreview implements TemplateableBlockLayoutInterface
{
    /**
     * @var ApiManager
     */
    protected ApiManager $apiManager;
    private EntityManager $entityManager;

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * @param EntityManager $entityManager
     * @param ApiManager $apiManager
     */


    public function __construct(EntityManager $entityManager, ApiManager $apiManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->translator = $translator;
    }
    /**
     * @inheritDoc
     */

    public function getApiManager(): ApiManager
    {
        return $this->apiManager;
    }

    public function getTranslatorInterface()
    {
        return $this->translator;
    }
    public function getLabel(): string
    {
        return 'Configurable Preview'; // @translate
    }

    public function prepareForm(PhpRenderer $view)
    {
        $view->headLink()->prependStylesheet($view->assetUrl('css/advanced-search.css', 'Omeka'));
        $view->headScript()->appendFile($view->assetUrl('js/advanced-search.js', 'Omeka'));
        $view->headScript()->appendFile($view->assetUrl('js/query-form.js', 'Omeka'));
        $view->headScript()->appendFile($view->assetUrl('js/browse-preview-block-layout.js', 'Omeka'));
    }

    public function initPropertySelect($name)
        {
            $property = new PropertySelect($name);
            $property->setApiManager($this->apiManager);
            $property->setTranslator($this->translator);
            return $property;
        }

    public function form(PhpRenderer $view, SiteRepresentation $site,
                         SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null
    ) {
        $defaults = [
            'resource_type' => 'items',
            'query' => '',
            'limit' => 100,
            'columns' => 2,
            'preview-title-property' => '[None]',
            'preview-subtitle-property' => '[None]',
            'preview-details-property' => '[None]',
            'card-style' => 'horizontal'
        ];

        $data = $block ? $block->data() + $defaults : $defaults;

        //for reasons that are unclear to me, the PropertySelect class can not access its required services when invoked
        //here via the forms add() method, so instantiating and setting up those services in this initPropertySelect
        //function.
        $titleProperty = $this->initPropertySelect("o:block[__blockIndex__][o:data][preview-title-property]");
        $titleProperty->setEmptyOption('[None]');
        $titleProperty->setLabel('Preview title property');
        $titleProperty->setOption('info','Select the property to use for the card title');
        $titleProperty->setOption('term_as_value', true);

        $subtitleProperty = $this->initPropertySelect("o:block[__blockIndex__][o:data][preview-subtitle-property]");
        $subtitleProperty->setEmptyOption('[None]');
        $subtitleProperty->setLabel('Preview subtitle property');
        $subtitleProperty->setOption('info','Select the property to use for the card subtitle');
        $subtitleProperty->setOption('term_as_value', '1');

        $detailsProperty = $this->initPropertySelect("o:block[__blockIndex__][o:data][preview-details-property]");
        $detailsProperty->setEmptyOption('[None]');
        $detailsProperty->setLabel('Preview details property');
        $detailsProperty->setOption('info','Select the property to use for the card details');
        $detailsProperty->setOption('term_as_value', '1');

        $form = new Form();
        $form->add([
            'name' => 'o:block[__blockIndex__][o:data][resource_type]',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Resource type', // @translate
                'value_options' => [
                    'items' => 'Items',  // @translate
                    'item_sets' => 'Item sets',  // @translate
                    'media' => 'Media',  // @translate
                ],
            ],
            'attributes' => [
                'class' => 'browse-preview-resource-type',
            ],
        ]);
        $form->add([
            'name' => 'o:block[__blockIndex__][o:data][card-style]',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Card Style', // @translate
                'value_options' => [
                    'horizontal' => 'horizontal (image and text are side-by-side)',  // @translate
                    'vertical' => 'vertical (image is above text)',  // @translate
                ],
            ],
            'attributes' => [
                'class' => 'browse-preview-resource-type',
            ],
        ]);

        $form->add([
            'name' => 'o:block[__blockIndex__][o:data][query]',
            'type' => OmekaElement\Query::class,
            'options' => [
                'label' => 'Search query', // @translate
                'info' => 'Display resources using this search query', // @translate
                'query_resource_type' => $data['resource_type'],
                'query_partial_excludelist' => ['common/advanced-search/site'],
            ],
        ]);
        $form->add([
            'name' => 'o:block[__blockIndex__][o:data][limit]',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Limit', // @translate
                'info' => 'Maximum number of resources to display in the preview.', // @translate
            ],
        ]);
        $form->add([
            'name' => 'o:block[__blockIndex__][o:data][columns]',
            'type' => Element\Select::class,
            'options' => [
                'value_options' => [
                    '1' => 'one',
                    '2' => 'two',
                    '3' => 'three'
                ],
                'label' => 'Maximum Columns', // @translate
                'info' => 'Number of cards per row on desktop displays (', // @translate
            ],
        ]);
        $form->add($titleProperty);
        $form->add($subtitleProperty);
        $form->add($detailsProperty);

        $form->setData([
            'o:block[__blockIndex__][o:data][resource_type]' => $data['resource_type'],
            'o:block[__blockIndex__][o:data][query]' => $data['query'],
            'o:block[__blockIndex__][o:data][limit]' => $data['limit'],
            'o:block[__blockIndex__][o:data][columns]' => $data['columns'],
            'o:block[__blockIndex__][o:data][card-style]' => $data['card-style'],
            'o:block[__blockIndex__][o:data][preview-title-property]' => $data['preview-title-property'],
            'o:block[__blockIndex__][o:data][preview-subtitle-property]' => $data['preview-subtitle-property'],
            'o:block[__blockIndex__][o:data][preview-details-property]' => $data['preview-details-property'],

        ]);

        return $view->formCollection($form);
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block, $templateViewScript = 'common/block-layout/configurable-preview')
    {
        $resourceType = $block->dataValue('resource_type', 'items');

        parse_str($block->dataValue('query'), $query);
        $originalQuery = $query;

        $site = $block->page()->site();
        if ($view->siteSetting('browse_attached_items', false)) {
            $query['site_attachments_only'] = true;
        }

        $query['site_id'] = $site->id();
        $query['limit'] = $block->dataValue('limit', 100);

        if (!isset($query['sort_by'])) {
            $query['sort_by_default'] = '';
            $query['sort_by'] = 'created';
        }
        if (!isset($query['sort_order'])) {
            $query['sort_order_default'] = '';
            $query['sort_order'] = 'desc';
        }

        //Show all resource components if none set
        if (empty($block->dataValue('components'))) {
            $components = ['resource-body', 'thumbnail'];
        } else {
            $components = $block->dataValue('components');
        }

        $response = $view->api()->search($resourceType, $query);
        $resources = $response->getContent();

        $resourceTypes = [
            'items' => 'item',
            'item_sets' => 'item-set',
            'media' => 'media',
        ];

        return $view->partial($templateViewScript, [
            'block' => $block,
            'resourceType' => $resourceTypes[$resourceType],
            'resources' => $resources,
            'titleProperty' => $block->dataValue('preview-title-property'),
            'subtitleProperty' => $block->dataValue('preview-subtitle-property'),
            'detailsProperty' => $block->dataValue('preview-details-property'),
            'title' => $components,
            'query' => $originalQuery,
            'columns' =>$block->dataValue('columns'),
            'cardStyle' => $block->dataValue('card-style')
        ]);
    }

    public function getEntityManager(): EntityManager
    {
        $em = $this->entityManager;
        return $em;
    }
}