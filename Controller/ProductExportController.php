<?php

namespace FATCHIP\K3\Application\Controller;

use FATCHIP\K3\Core\Export\ProductExport;
use OxidEsales\Eshop\Core\Registry;

class ProductExportController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{


    public function render() {


        $export = oxNew(ProductExport::class);
        $lang = Registry::getLang()->getBaseLanguage();
        $export->setLangId($lang);

        $export->setShopId(Registry::getConfig()->getShopId());
        echo json_encode($export->getData());


        exit;
    }
}