<?php

namespace FATCHIP\K3\Core;

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
        $secret = $this->getSavedSecret();
        if ($secret) {
            $this->setSecret($secret);
        }
        $token = $this->getSavedToken();
        if ($token) {
            $this->setToken($token);
        }
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
            $this->basketUrl = $this->getControllerUrl('fc_fck3_basketcontroller');
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
            $this->productExportUrl = $this->getControllerUrl('fc_fck3_productexportcontroller');
        }

        return $this->productExportUrl;
    }

    /**
     * @return string
     */
    public function getConnectorUrl(): string
    {
        if (!$this->connectorUrl) {
            $this->connectorUrl = $this->getControllerUrl('fc_fck3_connectorcontroller');
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
        return $url;
    }

    /**
     * @param string $productExportUrl
     */
    public function setProductExportUrl(string $productExportUrl): void
    {
        $this->productExportUrl = $productExportUrl;
    }

    /**
     * Retrurn saved secret
     *
     * @return string
     */
    public function getSavedSecret(): string
    {
        $secret = Registry::getConfig()->getShopConfVar('sFcK3AuthSecret', $this->getShopId(), 'fck3');
        if ($secret) {
            return $secret;
        }
        return '';
    }

    /**
     * Return saved token
     *
     * @return string
     */
    public function getSavedToken(): string
    {
        $token = Registry::getConfig()->getShopConfVar('sFcK3AuthToken', $this->getShopId(), 'fck3');
        if ($token) {
            return $token;
        }
        return '';
    }

    /**
     * Save auth information
     *
     * @return bool
     */
    public function save(): bool
    {
        $validation = oxNew(Validation::class);
        //check old secret against header
        if (!$validation->isValidSecret($this->getSavedSecret())) {
            Registry::get(Logger::class)->error('Secret is not valid',
            [__METHOD__]);
            return false;
        }
        Registry::getConfig()->saveShopConfVar('str', 'sFcK3AuthSecret', $this->getSecret(), $this->getShopId(),
            'fck3');
        Registry::getConfig()->saveShopConfVar('str', 'sFcK3AuthToken', $this->getToken(), $this->getShopId(),
            'fck3');
        return true;
    }


}