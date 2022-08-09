<?php

namespace FATCHIP\K3\Application\Controller;

use FATCHIP\K3\Application\Model\Configuration;
use FATCHIP\K3\Core\Output;
use OxidEsales\Eshop\Core\Registry;

class BasketController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * render
     *
     * @return string|void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function render()
    {
        if (!Registry::getConfig()->getConfigParam('blFcK3Active')) {
            Registry::get(Output::class)->json(['message' => 'Module not active.'], 503);
        }

        $configurationId = Registry::getRequest()->getRequestParameter('cfg');
        if ( !$configurationId ) {
            Registry::get(Output::class)->json(['message' => 'No cfg parameter found in request.'], 503);
        }

        $configurationId = 'test';
        $rawCfg = '{
    "variables":[
        {"variableId":104,"value":"blue"},
        {"variableId":105,"value":"red"}
    ],
    "bom":[
        {"article":"1402","qty":1}

    ]
}';
        $configurationString = json_decode($rawCfg,true);
        $configuration = oxNew(Configuration::class);
        $configuration->setConfigurationId($configurationId);
        $configuration->setConfiguration($configurationString);
        $basket = Registry::getSession()->getBasket();
        try {
            $basketArticles = $configuration->getBasketArticles();
            foreach( $basketArticles as $basketArticle ) {
                $basket->addToBasket($basketArticle['id'],$basketArticle['amount'],null, $basketArticle['persparam']);
            }
        } catch (\Exception $e) {
            \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($e->getMessage());
        }
        Registry::getUtils()->redirect(Registry::getConfig()->getShopHomeUrl().'cl=basket');
        exit;
    }
}