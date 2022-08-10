<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

$aModule = [
    'id' => 'fcobjectcodek3',
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
        \OxidEsales\Eshop\Application\Model\BasketItem::class => \FATCHIP\ObjectCodeK3\Extend\Application\Model\BasketItem::class,
        \OxidEsales\Eshop\Application\Model\Order::class => \FATCHIP\ObjectCodeK3\Extend\Application\Model\Order::class,
    ],
    'controllers' => [
        'fc_fcobjectcodek3_productexportcontroller' => \FATCHIP\ObjectCodeK3\Application\Controller\ProductExportController::class,
        'fc_fcobjectcodek3_connectorcontroller' => \FATCHIP\ObjectCodeK3\Application\Controller\ConnectorController::class,
        'fc_fcobjectcodek3_basketcontroller' => \FATCHIP\ObjectCodeK3\Application\Controller\BasketController::class,
    ],
    'templates' => [],
    'settings' => [
        [
            'group' => 'fcobjectcodek3',
            'name' => 'blFcObjectCodeK3Active',
            'type' => 'bool',
            'value' => true,
            'position' => 1,
        ],
        [
            'group' => 'fcobjectcodek3',
            'name' => 'sFcObjectCodeK3AuthToken',
            'type' => 'str',
            'value' => '',
            'position' => 10,
        ],
        [
            'group' => 'fcobjectcodek3',
            'name' => 'sFcObjectCodeK3AuthSecret',
            'type' => 'str',
            'value' => '',
            'position' => 20,
        ],
    ],
    'blocks' => [
        [
            'template' => 'page/checkout/inc/basketcontents_list.tpl',
            'block'    => 'checkout_basketcontents_basketitem_persparams',
            'file'     => 'Application/views/tpl/blocks/checkout_basketcontents_basketitem_persparams.tpl'
        ],
        [
            'template' => 'page/checkout/inc/basketcontents_table.tpl',
            'block'    => 'checkout_basketcontents_basketitem_persparams',
            'file'     => 'Application/views/tpl/blocks/checkout_basketcontents_basketitem_persparams.tpl'
        ],
        [
            'template' => 'email/html/order_cust.tpl',
            'block'    => 'email_html_order_cust_basketitem_persparams',
            'file'     => 'Application/views/tpl/blocks/email_html_order_cust_basketitem_persparams.tpl'
        ],
        [
            'template' => 'email/plain/order_cust.tpl',
            'block'    => 'email_plain_order_cust_persparams',
            'file'     => 'Application/views/tpl/blocks/email_plain_order_cust_persparams.tpl'
        ],
    ],
    'events' => [
        'onActivate' => '\FATCHIP\ObjectCodeK3\Core\Events::onActivate',
        'onDeactivate' => '\FATCHIP\ObjectCodeK3\Core\Events::onDeactivate',
    ],
];
