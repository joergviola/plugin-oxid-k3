<?php

namespace FATCHIP\K3\Application\Controller;

use FATCHIP\K3\Core\Connector;
use FATCHIP\K3\Core\Logger;
use OxidEsales\Eshop\Core\Registry;

class ConnectorController extends \OxidEsales\Eshop\Application\Controller\FrontendController
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
        $this->connectShop();
    }

    /**
     * Output export
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function connectShop()
    {
        try {
            $token = Registry::getRequest()->getRequestParameter('token');
            $secret = Registry::getRequest()->getRequestParameter('secret');
            if (!$token || !$secret) {
                echo 'ERROR';
                Registry::get(Logger::class)->error('No token or secret given', [
                    __METHOD__
                ]);
                exit;
            }
            $connector = oxNew(Connector::class);
            $connector->setShopId(Registry::getConfig()->getShopId());
            $connector->setToken($token);
            $connector->setSecret($secret);
            if ($connector->save()) {
                $output = [
                    'cart' => $connector->getBasketUrl(),
                    'articles' => $connector->getProductExportUrl()
                ];
                \OxidEsales\Eshop\Core\Registry::getUtils()->setHeader("Content-Type: application/json; charset=utf8");
                echo json_encode($output);
            }
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Could not connect shop', [
                $e->getMessage(),
                __METHOD__
            ]);
            echo 'ERROR';
        }
        exit;
    }
}