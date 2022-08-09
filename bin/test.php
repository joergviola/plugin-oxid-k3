<?php

require dirname(__FILE__).'/../../../../bootstrap.php';

/*
$export = oxNew(ProductExport::class);
$lang = Registry::getLang()->getBaseLanguage();
$export->setLangId(1);
$export->setShopId(1);
$export->setCurrencyId(1);

\OxidEsales\Eshop\Core\Registry::getUtils()->setHeader("Content-Type: application/json; charset=utf8");
echo json_encode($export->getData());*/
$rawCfg = '{
    "variables":[
        {"variableId":104,"value":"blue"},
        {"variableId":105,"value":"red"}
    ],
    "bom":[
        {"article":"1402","qty":1}

    ]
}';
$cfg = json_decode($rawCfg);
$configuraton = oxNew(\FATCHIP\K3\Application\Model\Configuration::class);
$configuraton->setConfigurationId('test');
$configuraton->setConfiguration($cfg);
$basketData = $configuraton->getBasketProducts();
dumpVar($basketData);