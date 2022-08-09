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

        try {
            $service = oxNew(\FATCHIP\K3\Core\Service\Configuration::class);
            $service->addToBasket($configurationId);
        } catch (\Exception $e) {
            \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($e->getMessage());
        }

        Registry::getUtils()->redirect(Registry::getConfig()->getShopHomeUrl().'cl=basket');
        exit;
    }
}