<?php

namespace ObjectCode\K3\Extend\Application\Model;


use ObjectCode\K3\Core\Logger;
use ObjectCode\K3\Core\Service\Configuration;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;

class Order extends Order_Parent
{
    /**
     * Send order to shop owner and user
     *
     * @param \OxidEsales\Eshop\Application\Model\User $oUser order user
     * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket current order basket
     * @param \OxidEsales\Eshop\Application\Model\UserPayment $oPayment order payment
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "sendOrderByEmail" in next major
     */
    /* @TODO add order endpoint, waiting for k3 api to be finished
     *
    protected function _sendOrderByEmail(
        $oUser = null,
        $oBasket = null,
        $oPayment = null
    ) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        try {
            $articles = $this->getOrderArticles();
            if ($articles) {
                $service = oxNew(Configuration::class);
                foreach ($articles as $orderArticle) {
                    if ($orderArticle->oxorderarticles__ock3cfg->value && $orderArticle->oxorderarticles__ock3codeapp->value) {
                        $service->setOrdered($orderArticle->oxorderarticles__ock3cfg->value,
                            $orderArticle->oxorderarticles__ock3codeapp->value);
                    }
                }
            }
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Could not set configuration as ordered', [
                $e->getMessage(),
                __METHOD__
            ]);
        }
        return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
    }*/

    /**
     * Creates OrderArticle objects and assigns to them basket articles.
     * Updates quantity of sold articles (\OxidEsales\Eshop\Application\Model\Article::updateSoldAmount()).
     *
     * @param array $aArticleList article list
     * @deprecated underscore prefix violates PSR12, will be renamed to "setOrderArticles" in next major
     */
    protected function _setOrderArticles($aArticleList) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_setOrderArticles($aArticleList);

        try {
            if ($this->_oArticles) {
                $this->ocSetK3Configuration($this->_oArticles);
            }
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Error while trying to add data to oxorderarticles', [
                $e->getMessage(),
                __METHOD__,
                'oxid' => $this->oxorder__oxid->value,
                'oxordernr' => $this->oxorder__oxordernr->value,
            ]);
        }
    }

    /**
     * Set k3 configuration to order articles
     *
     * @param $articles
     * @return void
     */
    protected function ocSetK3Configuration($articles)
    {
        if ($articles) {
            foreach ($articles as $key => $orderArticle) {
                if ($orderArticle->oxorderarticles__oxpersparam->rawValue) {
                    $params = unserialize($orderArticle->oxorderarticles__oxpersparam->rawValue);
                    if ($params && isset($params['k3'])) {
                        $configuration = unserialize(base64_decode($params['k3']));
                        if ($configuration && isset($configuration['configurationId']) && $configuration['appCode']) {
                            $articles[$key]->oxorderarticles__ock3cfg = new Field($configuration['configurationId']);
                            $articles[$key]->oxorderarticles__ock3codeapp = new Field($configuration['appCode']);
                        } else {
                            Registry::get(Logger::class)->error('Could not save configuration on order article', [
                                __METHOD__,
                                'oxid' => $orderArticle->oxorderarticles__oxid->value,
                                'oxorderid' => $this->oxorder__oxid->value,
                                'oxordernr' => $this->oxorder__oxordernr->value,
                            ]);
                        }
                    }
                }
            }
        }
    }
}