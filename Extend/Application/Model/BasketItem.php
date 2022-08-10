<?php

namespace FATCHIP\ObjectCodeK3\Extend\Application\Model;

class BasketItem extends BasketItem_Parent
{
    /**
     * Return true if article has configuration
     *
     * @return bool
     */
    public function fcHasK3Configuration(): bool
    {
        $params = $this->fcGetK3Configuration();
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
    public function fcGetK3Configuration()
    {
        $params = $this->getPersParams();
        if ($params && isset($params['k3'])) {
            return unserialize(base64_decode($params['k3']));
        }
        return [];
    }
}