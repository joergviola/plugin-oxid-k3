<?php

namespace FATCHIP\ObjectCodeK3\Core;

use OxidEsales\Eshop\Core\Registry;

class Connector
{
    /**
     * @var string
     */
    protected string $token = '';

    /**
     * @var string
     */
    protected string $secret = '';

    /**
     * @var string
     */
    protected string $basketUrl = '';

    /**
     * @var string
     */
    protected string $productExportUrl = '';

    /**
     * @var string
     */
    protected string $connectorUrl = '';

    /**
     * Shop id
     *
     * @var int
     */
    protected int $shopId = 1;

    /**
     * load secret and token
     */
    public function __construct()
    {
        $this->shopId = Registry::getConfig()->getShopId();
    }

    /**
     * @return int
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * @param int $shopId
     */
    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        if (!$this->token) {
            $token = Registry::getConfig()->getShopConfVar('sFcObjectCodeK3AuthToken', $this->getShopId(), 'module:fcobjectcodek3');
            if ($token) {
                $this->token = $token;
            }
        }
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        if (!$this->secret) {
            $secret = Registry::getConfig()->getShopConfVar('sFcObjectCodeK3AuthSecret', $this->getShopId(), 'module:fcobjectcodek3');
            if ($secret) {
                $this->secret = $secret;
            }
        }
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getBasketUrl(): string
    {
        if (!$this->basketUrl) {
            $this->basketUrl = $this->getControllerUrl('fc_fcobjectcodek3_basketcontroller');
        }
        return $this->basketUrl;
    }

    /**
     * @param string $basketUrl
     */
    public function setBasketUrl(string $basketUrl): void
    {
        $this->basketUrl = $basketUrl;
    }

    /**
     * @return string
     */
    public function getProductExportUrl(): string
    {
        if (!$this->productExportUrl) {
            $this->productExportUrl = $this->getControllerUrl('fc_fcobjectcodek3_productexportcontroller');
        }

        return $this->productExportUrl;
    }

    /**
     * @param string $productExportUrl
     */
    public function setProductExportUrl(string $productExportUrl): void
    {
        $this->productExportUrl = $productExportUrl;
    }

    /**
     * @return string
     */
    public function getConnectorUrl(): string
    {
        if (!$this->connectorUrl) {
            $this->connectorUrl = $this->getControllerUrl('fc_fcobjectcodek3_connectorcontroller');
        }
        return $this->connectorUrl;
    }

    /**
     * @param string $connectorUrl
     */
    public function setConnectorUrl(string $connectorUrl): void
    {
        $this->connectorUrl = $connectorUrl;
    }

    /**
     * Return controller url
     *
     * @param $controller
     * @return string
     */
    protected function getControllerUrl($controller): string
    {
        $url = Registry::getSeoEncoder()->getStaticUrl(Registry::getConfig()->getShopHomeUrl() . 'cl=' . $controller);
        if (!$url) {
            $url = Registry::getConfig()->getShopHomeUrl() . 'cl=' . $controller;
        }
        return Registry::getUtilsUrl()->cleanUrl($url, ['force_sid', 'sid']);
    }

    /**
     * Save auth information
     *
     * @return bool
     */
    public function save(): bool
    {
        Registry::getConfig()->saveShopConfVar('str', 'sFcObjectCodeK3AuthSecret', $this->getSecret(),
            $this->getShopId(),
            'module:fcobjectcodek3');
        Registry::getConfig()->saveShopConfVar('str', 'sFcObjectCodeK3AuthToken', $this->getToken(), $this->getShopId(),
            'module:fcobjectcodek3');
        return true;
    }

}