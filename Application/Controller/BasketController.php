<?php

namespace FATCHIP\K3\Application\Controller;

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

        dumpVar(1);
        exit;
    }
}