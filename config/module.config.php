<?php
namespace Mason;

use Mason\Media\Renderer\EmbeddedMedia;
use Mason\Service\BlockLayout\ConfigurablePreviewFactory;
use Mason\Service\BlockLayout\DisableSubNavFactory;
use Mason\Service\BlockLayout\ListOfExhibitsFactory;
use Mason\Service\BlockLayout\ExhibitContentsFactory;
use Mason\View\ImplodePropertyValues;

return [
    'block_layouts' => [

        'factories'  => [
            'listOfExhibits' => ListOfExhibitsFactory::class,
            'exhibitContents' => ExhibitContentsFactory::class,
            'disableSubNav' => DisableSubNavFactory::class,
            'configurablePreview' => ConfigurablePreviewFactory::class

        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ]
    ],
    'view_helpers' => [
        'invokables'=> [
            'implodePropertyValues' => ImplodePropertyValues::class
        ]
    ],
    'media_ingesters' => [
        'factories' => [
            'embedded_media' => Service\Media\Ingester\EmbeddedMediaFactory::class,
        ]
    ],
    'media_renderers' => [
        'invokables' => [
            'embedded_media' => EmbeddedMedia::class
        ]
    ],
    'csv_import' => [
        'media_ingester_adapter' => [
            'embedded_media' => null,
        ]
    ]

    ];