<?php

namespace FATCHIP\K3\Core\Auth;

use OxidEsales\Eshop\Core\Registry;

class Shop
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
        $secret = Registry::getConfig()->getShopConfVar('sFcK3AuthSecret', $this->getShopId(), 'fck3');
        if ($secret) {
            $this->setSecret($secret);
        }
        $token = Registry::getConfig()->getShopConfVar('sFcK3AuthToken', $this->getShopId(), 'fck3');
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
     * Save auth information
     *
     * @return bool
     */
    public function save(): bool
    {
        $headers = $this->getHeaders();
        if (!$this->isValidHeader($headers)) {
            return false;
        }
        Registry::getConfig()->saveShopConfVar('str', 'sFcK3AuthSecret', $this->getSecret(), $this->getShopId(),
            'fck3');
        Registry::getConfig()->saveShopConfVar('str', 'sFcK3AuthToken', $this->getToken(), $this->getShopId(), 'fck3');
        return true;
    }

    /**
     * Check if header is valid
     *
     * @param $headers
     * @return bool
     */
    protected function isValidHeader($headers): bool
    {
        $secret = $this->getSecret();
        if ($secret && (!isset($headers['X-Secret']) || $secret != $headers['X-Secret'])) {
            return false;
        }
        return true;
    }

    /**
     * Return headers
     *
     * @return array|false
     */
    protected function getHeaders()
    {
        return getallheaders();
    }
}