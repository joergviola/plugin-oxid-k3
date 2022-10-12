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
    }
}