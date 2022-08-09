<?php

namespace FATCHIP\K3\Core\Service;

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
     * @return \FATCHIP\K3\Application\Model\Configuration|mixed
     */
    protected function getConfigurationModel($configurationId)
    {
        $configurationJson = $this->loadConfiguration($configurationId);
        $configurationArray = json_decode($configurationJson);
        $configuration = oxNew(\FATCHIP\K3\Application\Model\Configuration::class);
        $configuration->setConfigurationId($configurationId);
        $configuration->setConfiguration($configurationArray);
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
    }


}