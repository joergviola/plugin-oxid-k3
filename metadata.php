<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

$aModule = [
    'id' => 'fck3',
    'title' => 'FATCHIP Modul K3 Objectcode',
    'description' => [
        'de' => 'Integration von K3 Objectcode',
    ],
    'version' => '1.0.0',
    'author' => 'FATCHIP GmbH',
    'email' => 'support@fatchip.de',
    'url' => '',
    'thumbnail' => 'FC-Logo_24.png',
    'extend' => [
        \OxidEsales\Eshop\Application\Model\BasketItem::class => \FATCHIP\K3\Extend\Application\Model\BasketItem::class,
    ],
    'controllers' => [
        'fc_fck3_productexportcontroller' => \FATCHIP\K3\Application\Controller\ProductExportController::class,
        'fc_fck3_connectorcontroller' => \FATCHIP\K3\Application\Controller\ConnectorController::class,
        'fc_fck3_basketcontroller' => \FATCHIP\K3\Application\Controller\BasketController::class,
    ],
    'templates' => [],
    'settings' => [
        [
            'group' => 'fck3',
            'name' => 'blFcK3Active',
            'type' => 'bool',
            'value' => true,
            'position' => 1,
        ],
        [
            'group' => 'fck3',
            'name' => 'sFcK3AuthToken',
            'type' => 'str',
            'value' => '',
            'position' => 10,
        ],
        [
            'group' => 'fck3',
            'name' => 'sFcK3AuthSecret',
            'type' => 'str',
            'value' => '',
            'position' => 20,
        ],
    ],
    'blocks' => [
    ],
    'events' => [],
];
