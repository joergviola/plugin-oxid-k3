<?php

namespace FATCHIP\K3\Application\Controller;

use FATCHIP\K3\Core\Export\ProductExport;
use FATCHIP\K3\Core\Logger;
use FATCHIP\K3\Core\Output;
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
        if (!Registry::getConfig()->getConfigParam('blFcK3Active')) {
            Registry::get(Output::class)->json(['message' => 'Module not active.'], 503);
        }

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
            $export->setCurrencyId(Registry::getConfig()->getShopCurrency());
            Registry::get(Output::class)->json($export->getData(), 200);
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Could not export articles', [
                $e->getMessage(),
                __METHOD__
            ]);
        }
        exit;
    }
}