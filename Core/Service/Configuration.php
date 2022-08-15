<?php

namespace FATCHIP\ObjectCodeK3\Core\Service;

use FATCHIP\ObjectCodeK3\Core\Request;
use OxidEsales\Eshop\Core\Registry;

class Configuration
{
    /**
     * Add configuration to basket
     *
     * @param $configurationId
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\ArticleInputException
     * @throws \OxidEsales\Eshop\Core\Exception\NoArticleException
     * @throws \OxidEsales\Eshop\Core\Exception\OutOfStockException
     */
    public function addToBasket($configurationId)
    {
        $configuration = $this->getConfigurationModel($configurationId);
        $basket = Registry::getSession()->getBasket();
        $basketArticles = $configuration->getBasketProducts();
        foreach ($basketArticles as $basketArticle) {
            $basketItem = $basket->addToBasket($basketArticle['id'], $basketArticle['amount'], null,
                $this->getFormattedParams($basketArticle['params']));
            $this->setBasketItemPrice($basket, $basketItem, $basketArticle['price']);
        }
    }

    /**
     * Return formatted params
     *
     * @param $configurationParams
     * @return array
     */
    protected function getFormattedParams($configurationParams): array
    {
        $encodedConfiguration = base64_encode(serialize($configurationParams));
        return [
            'k3' => $encodedConfiguration
        ];
    }

    /**
     * Set basket item price
     *
     * @param $basket
     * @param $basketItem
     * @param $price
     * @return void
     */
    protected function setBasketItemPrice($basket, $basketItem, $price)
    {
        if ($basketItem && $price) {
            $oPrice = oxNew(\OxidEsales\Eshop\Core\Price::class);
            if ($basket->isCalculationModeNetto()) {
                $oPrice->setNettoPriceMode();
            } else {
                $oPrice->setBruttoPriceMode();
            }
            $oPrice->setPrice($price);
            $basketItem->setPrice($oPrice);
        }
    }

    /**
     * Create configuration model
     *
     * @param $configurationId
     * @return \FATCHIP\ObjectCodeK3\Application\Model\Configuration|mixed
     */
    protected function getConfigurationModel($configurationId)
    {
        $configurationJson = $this->loadConfiguration($configurationId);
        $configurationObject = json_decode($configurationJson);
        $configuration = oxNew(\FATCHIP\ObjectCodeK3\Application\Model\Configuration::class);
        $configuration->setConfiguration($configurationObject);
        return $configuration;
    }

    /**
     * Load configuration
     * @param $configurationId
     * @return string
     */
    protected function loadConfiguration($configurationId)
    {
        $configuration = oxNew(Request::class)->getConfiguration($configurationId);
        if ($configuration) {
            return $configuration;
        }
    }

    /**
     * Set ordered state
     *
     * @param $configurationId
     * @param $app
     * @return void
     */
    public function setOrdered($configurationId, $app)
    {
        return oxNew(Request::class)->setOrdered($configurationId, $app);
    }
}