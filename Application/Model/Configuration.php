<?php

namespace FATCHIP\K3\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use function oxNew;

class Configuration
{
    /**
     * @var string
     */
    protected string $configurationId = '';

    /**
     * @var array
     */
    protected array $configuration = [];

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
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * Return basket articles
     *
     * @return array
     */
    public function getBasketArticles(): array
    {
        $basketArticles = [];
        $configuration = $this->getConfiguration();
        $articles = $configuration['bom'];
        foreach ($articles as $article) {
            $basketArticles[] = $this->getBasketArticle($article, $configuration['variables']);

        }
        return $basketArticles;
    }

    /**
     * Return basket article
     *
     * @param $article
     * @param array $variables
     * @return array
     */
    protected function getBasketArticle($article, array $variables = []): array
    {
        return [
            'id' => $this->getOxidFromArticleNumber($article['article']),
            'amount' => $article['qty'],
            'persparam' => $this->getPersParams($variables),
        ];
    }

    /**
     * Return persparams
     *
     * @param $variables
     * @return array
     */
    protected function getPersParams($variables): array
    {
        $params = [];
        $params['k3']['id'] = $this->getConfigurationId();
        foreach ($variables as $variable) {
            $params['k3']['variables'][] = [
                'id' => $variable['variableId'],
                'label' => $variable['label'],
                'value' => $variable['value'],
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