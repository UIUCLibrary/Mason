<?php
namespace Mason;

use Mason\Service\BlockLayout\DisableSubNavFactory;
use Mason\Service\BlockLayout\ListOfExhibitsFactory;
use Mason\Service\BlockLayout\ExhibitContentsFactory;

use Mason\Service\Form\Element\TeamSelectFactory;

return [
    'block_layouts' => [

        'factories'  => [
            'listOfExhibits' => ListOfExhibitsFactory::class,
            'exhibitContents' => ExhibitContentsFactory::class,
            'disableSubNav' => DisableSubNavFactory::class

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

];