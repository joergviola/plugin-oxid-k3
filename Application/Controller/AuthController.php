<?php

namespace FATCHIP\K3\Application\Controller;

use FATCHIP\K3\Core\Auth\Shop;
use OxidEsales\Eshop\Core\Registry;

class AuthController extends \OxidEsales\Eshop\Application\Controller\FrontendController
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
        $this->initK3Auth();
    }

    /**
     * Output export
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function initK3Auth()
    {
        try {
            $token = Registry::getRequest()->getRequestParameter('token');
            $secret = Registry::getRequest()->getRequestParameter('secret');
            $auth = oxNew(Shop::class);
            $auth->setShopId(Registry::getConfig()->getShopId());
            $auth->setToken($token);
            $auth->setSecret($secret);
            if ($auth->save()) {
                $output = [
                    'cart' => $auth->getBasketUrl(),
                    'articles' => $auth->getProductExportUrl()
                ];
                \OxidEsales\Eshop\Core\Registry::getUtils()->setHeader("Content-Type: application/json; charset=utf8");
                echo json_encode($output);
            }
        } catch (\Exception $e) {
            Registry::getLogger()->error('Could not export articles', [
                $e->getMessage(),
                __METHOD__
            ]);
        }
        exit;
    }
}