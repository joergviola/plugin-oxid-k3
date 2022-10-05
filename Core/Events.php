<?php

namespace ObjectCode\K3\Core;

use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

class Events
{
    /**
     * Execute action on activate event.
     *
     * @return void
     * @throws
     */
    public static function onActivate()
    {
        self::addData();
        self::regenerateViews();
        self::clearTmp();
    }

    /**
     * Execute action on deactivate event.
     *
     * @return void
     * @throws
     */
    public static function onDeactivate()
    {
        self::clearTmp();
    }

    /**
     * Regenerates database view-tables.
     *
     * @return void
     * @throws
     */
    public static function regenerateViews()
    {
        $shop = oxNew(Shop::class);
        $shop->generateViews();
    }

    /**
     * Clear tmp dir and smarty cache.
     *
     * @return void
     */
    public static function clearTmp()
    {
        $tmpDir = getShopBasePath() . "/tmp/";
        $smartyDir = $tmpDir . "smarty/";

        foreach (glob($tmpDir . "*.txt") as $file) {
            unlink($file);
        }
        foreach (glob($smartyDir . "*.php") as $file) {
            unlink($file);
        }
    }


    /**
     * Add data
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected static function addData()
    {
        //add attribute
        $query = "select oxid from oxattribute where oxid = 'k3product' limit 1";
        $oxid = DatabaseProvider::getDb()->getOne($query);
        if (!$oxid) {
            $queryInsert = "insert into oxattribute (OXID, OXSHOPID, OXTITLE, OXTITLE_1, OXTITLE_2, OXPOS) values(?,?,?,?,?,?)";
            DatabaseProvider::getDb()->execute($queryInsert,
                ['k3product', Registry::getConfig()->getShopId(), 'K3', 'K3', 'K3', 0]);
        }

        self::addSeo('index.php?cl=oc_ock3_connectorcontroller', 'k3/connect/');
        self::addSeo('index.php?cl=oc_ock3_basketcontroller', 'k3/cart/');
        self::addSeo('index.php?cl=oc_ock3_productexportcontroller', 'k3/articles/');
    }

    /**
     * Add seo url
     *
     * @param $staticUrl
     * @param $seoUrl
     * @return void
     */
    protected static function addSeo($staticUrl, $seoUrl)
    {
        $urls = [
            'oxseo__oxobjectid' => md5($staticUrl),
            'oxseo__oxstdurl' => $staticUrl,
            'oxseo__oxseourl' => [
                0 => $seoUrl
            ],
        ];
        // Shop id and language id is hardcoded to prevent multiple urls
        // the k3 configuration only allows 1 url
        // control over shop and language is handled over request oxid default parameters (eg. shp, lang)
        \OxidEsales\Eshop\Core\Registry::getSeoEncoder()->encodeStaticUrls($urls, 1, 0);
    }

}