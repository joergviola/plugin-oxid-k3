<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

$aModule = [
    'id' => 'fck3',
    'title' => 'FATCHIP Modul K3 Objectcode',
    'description' => [
        'de' => 'Integration von K3 von Objectcode',
    ],
    'version' => '1.0.0',
    'author' => 'FATCHIP GmbH',
    'email' => 'support@fatchip.de',
    'url' => '',
    'thumbnail' => 'FC-Logo_24.png',
    'extend' => [],
    'controllers' => [
        'fc_fck3_productexportcontroller' => \FATCHIP\K3\Application\Controller\ProductExportController::class,
        'fc_fck3_authcontroller' => \FATCHIP\K3\Application\Controller\AuthController::class,
        'fc_fck3_basketcontroller' => \FATCHIP\K3\Application\Controller\BasketController::class,
    ],
    'templates' => [],
    'settings' => [
    ],
    'blocks' => [
    ],
    'events' => [],
];
