<?php

namespace FATCHIP\ObjectCodeK3\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use function oxNew;

class Configuration
{
    /**
     * @var string
     */
    protected string $configurationId = '';

    /**
     * @var object
     */
    protected object $configuration;

    /**
     * @return string
     */
    public function getConfigurationId(): string
    {
        return $this->configurationId;
    }

    /**
     * @param string $configurationId
     */
    public function setConfigurationId(string $configurationId): void
    {
        $this->configurationId = $configurationId;
    }

    /**
     * @return object
     */
    public function getConfiguration(): object
    {
        return $this->configuration;
    }

    /**
     * @param object $configuration
     */
    public function setConfiguration(object $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * Return basket articles
     *
     * @return array
     */
    public function getBasketProducts(): array
    {
        $basketArticles = [];
        $configuration = $this->getConfiguration();
        $articles = $configuration->bom;
        foreach ($articles as $article) {
            $basketArticles[] = $this->getBasketProduct($article, $configuration->variables);

        }
        return $basketArticles;
    }

    /**
     * Return basket article
     *
     * @param $article
     * @param array|null $variables
     * @return array
     */
    protected function getBasketProduct($article, array $variables = null): array
    {
        return [
            'id' => $this->getOxidFromArticleNumber($article->article),
            'amount' => $article->qty,
            'params' => $this->getBasketProductParams($variables),
        ];
    }

    /**
     * Return persparams
     *
     * @param $variables
     * @return array
     */
    protected function getBasketProductParams($variables): array
    {
        $params = [];
        $params['configurationId'] = $this->getConfigurationId();
        $params['appCode'] = 'testAppCode';
        foreach ($variables as $variable) {
            $params['variables'][] = [
                'id' => $variable->variableId,
                'label' => $variable->label,
                'value' => $variable->value,
            ];
        }
        return $params;
    }

    /**
     * Return oxid form article number
     *
     * @param string $articleNumber
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    protected function getOxidFromArticleNumber(string $articleNumber): string
    {
        $query = "select oxid from oxarticles where oxartnum = :oxartnum limit 1";
        $oxid = DatabaseProvider::getDb()->getOne($query, [':oxartnum' => $articleNumber]);
        if ($oxid) {
            return $oxid;
        }
        throw new \Exception('No article oxid found for article number: ' . $articleNumber);
    }

}