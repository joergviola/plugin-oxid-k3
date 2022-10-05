<?php

namespace ObjectCode\K3\Extend\Application\Model;

use OxidEsales\Eshop\Core\Exception\ArticleInputException;

class BasketItem extends BasketItem_Parent
{
    /**
     * Return true if article has configuration
     *
     * @return bool
     */
    public function ocHasK3Configuration(): bool
    {
        $params = $this->ocGetK3Configuration();
        if ($params && count($params) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Return configuration
     *
     * @return array
     */
    public function ocGetK3Configuration()
    {
        $params = $this->getPersParams();
        if ($params && isset($params['k3'])) {
            return unserialize(base64_decode($params['k3']));
        }
        return [];
    }

    /**
     * Sets item amount and weight which depends on amount
     * ( oxbasketitem::dAmount, oxbasketitem::dWeight )
     *
     * @param double $dAmount amount
     * @param bool $blOverride Whether to override current amount.
     * @param string $sItemKey item key
     *
     * @throws oxArticleInputException
     * @throws oxOutOfStockException
     */
    public function setAmount($dAmount, $blOverride = true, $sItemKey = null)
    {
        $configuration = $this->ocGetK3Configuration();
        if ($configuration && isset($configuration['amount']) && $dAmount != $configuration['amount']) {
            $exception = oxNew(ArticleInputException::class);
            $exception->setMessage(\OxidEsales\Eshop\Core\Registry::getLang()->translateString('OC_K3_EXCEPTION_ARTICLE_NO_VALID_AMOUNT'));
            $exception->setArticleNr($this->getProductId());
            $exception->setProductId($this->getProductId());
            throw $exception;
        }
        return parent::setAmount($dAmount, $blOverride, $sItemKey);
    }
}