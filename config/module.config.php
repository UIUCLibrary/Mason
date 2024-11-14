<?php
namespace Mason;

use Mason\Media\Renderer\EmbeddedMedia;
use Mason\Service\BlockLayout\ConfigurablePreviewFactory;
use Mason\Service\BlockLayout\DisableSubNavFactory;
use Mason\Service\BlockLayout\ListOfExhibitsFactory;
use Mason\Service\BlockLayout\ExhibitContentsFactory;

use Mason\Service\Form\Element\TeamSelectFactory;
use Omeka\Entity\Media;

return [
    'block_layouts' => [

        'factories'  => [
            'listOfExhibits' => ListOfExhibitsFactory::class,
            'exhibitContents' => ExhibitContentsFactory::class,
            'disableSubNav' => DisableSubNavFactory::class,
            'configurablePreview' => ConfigurablePreviewFactory::class

        ]
    ],
    'form_elements' => [
        'factories' => [
            Form\Element\TeamSelect::class => TeamSelectFactory::class,
            Form\Element\AllTeamSelect::class => Service\Form\Element\AllTeamSelectFactory::class,
            Form\Element\AllSiteSelect::class => Service\Form\Element\AllSiteSelectFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
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