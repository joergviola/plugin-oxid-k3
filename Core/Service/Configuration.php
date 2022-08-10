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
            $encodedConfiguration = base64_encode(serialize($basketArticle['params']));
            $params = [
                'k3' => $encodedConfiguration
            ];
            $basket->addToBasket($basketArticle['id'], $basketArticle['amount'], null, $params);
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
        $configuration->setConfigurationId($configurationId);
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
        $rawCfg = '{
    "variables":[
        {"variableId":104,"value":"blue"},
        {"variableId":105,"value":"red"}
    ],
    "bom":[
        {"article":"1402","qty":1}

    ]
}';
        return $rawCfg;
        $configuration = oxNew(Request::class)->getConfiguration($configurationId);
        if ( $configuration ) {
            return $configuration;
        }
    }

    public function setOrdered($configurationId, $appCode) {

    }


}