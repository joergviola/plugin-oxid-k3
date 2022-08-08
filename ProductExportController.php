<?php

namespace FATCHIP\K3\Application\Controller;

use FATCHIP\K3\Core\Export\ProductExport;
use FATCHIP\K3\Core\Logger;
use OxidEsales\Eshop\Core\Registry;

class ProductExportController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * render
     *
     * @return string|void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function render()
    {
        $this->outputExport();
    }

    /**
     * Output export
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function outputExport()
    {
        try {
            $export = oxNew(ProductExport::class);
            $lang = Registry::getLang()->getBaseLanguage();
            $export->setLangId($lang);
            $export->setShopId(Registry::getConfig()->getShopId());
            $currency = Registry::getConfig()->getActShopCurrencyObject();
            $export->setCurrency($currency->name);

            \OxidEsales\Eshop\Core\Registry::getUtils()->setHeader("Content-Type: application/json; charset=utf8");
            echo json_encode($export->getData());
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Could not export articles', [
                $e->getMessage(),
                __METHOD__
            ]);
        }
        exit;
    }
}