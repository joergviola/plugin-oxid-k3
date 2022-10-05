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
}