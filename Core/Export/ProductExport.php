<?php

namespace FATCHIP\K3\Core\Export;

use Doctrine\DBAL\FetchMode;
use FATCHIP\K3\Core\Export\Model\Product;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class ProductExport
{
    /** Shop ID
     *
     */
    protected int $shopId = 1;

    /**
     * Language
     *
     * @var null
     */
    protected int $langId = 0;

    /**
     * Export data
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Returns attribute ident to identify k3 products
     *
     * @var string
     */
    protected string $attributeOxid = 'k3product';

    /**
     * Returns attribute ident to identify k3 products
     *
     * @var string
     */
    protected string $attributeValue = 'y';

    /**
     * Set shop id
     *
     * @param int $shopId
     * @return void
     */
    public function setShopId(int $shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * Return shop id
     *
     * @return int
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * Set lang id
     *
     * @param int $langId
     * @return void
     */
    public function setLangId(int $langId)
    {
        $this->langId = $langId;
    }

    /**
     * Return article table
     *
     * @param bool $core
     * @return string
     */
    public function getArticleTable(bool $core = false): string
    {
        if ($core) {
            return 'oxarticles';
        }
        $viewNameGenerator = Registry::get(\OxidEsales\Eshop\Core\TableViewNameGenerator::class);
        return $viewNameGenerator->getViewName('oxarticles', $this->getLangId(), $this->getShopId());
    }

    /**
     * Return oxobject2attribute table
     *
     * @param bool $core
     * @return string
     */
    public function getOxObject2AttributeTable(bool $core = false): string
    {
        if ($core) {
            return 'oxobject2attribute';
        }
        $viewNameGenerator = Registry::get(\OxidEsales\Eshop\Core\TableViewNameGenerator::class);
        return $viewNameGenerator->getViewName('oxobject2attribute', $this->getLangId(), $this->getShopId());
    }

    /**
     * Return lang id
     *
     * @return int
     */
    public function getLangId(): int
    {
        return $this->langId;
    }

    public function getData()
    {
        $this->data['lang'] = $this->getLangId();
        $this->data['shopId'] = $this->getShopId();
        $this->getExportProducts();
        return $this->data;
    }

    public function generateData()
    {

    }

    protected function getExportProducts()
    {
        $products = [];
        $query = $this->getProductQuery();
        $result = DatabaseProvider::getDb()->getAll($query);
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                $products[$row[0]] = $this->getExportProduct($row[0]);
            }
        }
        #dumpVar($result);
        return $products;
    }

    protected function getExportProduct($id)
    {
        $article = oxNew(Article::class);
        if ($article->load($id)) {
            $exportProduct = oxNew(Product::class);
            $exportProduct->setNo($article->getFieldData('oxartnum'));
            $exportProduct->setName($article->getFieldData('oxtitle'));
            $exportProduct->setDescription($article->getLongDesc());
            $category = $article->getCategory();
            $exportProduct->setCategory($category->getFieldData('oxtitle'));
            $prices = $this->getExportProductPrices($article);
            $exportProduct->setPrices($prices);
        }
        return $exportProduct;
    }

    protected function getExportProductPrices($article) : array {

        $price = $article->getPrice();
        $tprice = $article->getTPrice();
        $amountPrices = $article->getAmountPriceList();
        $price = [
            ''
        ];



        return [];
    }

    /**
     * Return product query
     *
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    protected function getProductQuery(): string
    {
        $o2aTable = $this->getOxObject2AttributeTable();
        $query = "select count(oxid) from $o2aTable where $o2aTable.oxattrid = '{$this->attributeOxid}' and $o2aTable.oxvalue = '{$this->attributeValue}' limit 1";
        $selectedCount = DatabaseProvider::getDb()->getOne($query);
        if ($selectedCount && $selectedCount > 0) {
            return $this->getSelectedProductsQuery();
        }
        return $this->getAllProductsQuery();
    }

    /**
     * Returns query to get all products
     *
     * @return string
     */
    protected function getAllProductsQuery(): string
    {
        $table = $this->getArticleTable();
        return "select oxid from $table where oxactive = 1";
    }

    /**
     * Returns query to get all selected products
     *
     * @return string
     */
    protected function getSelectedProductsQuery(): string
    {
        $table = $this->getArticleTable();
        $o2aTable = $this->getOxObject2AttributeTable();
        return "select $table.oxid from $table join $o2aTable on $o2aTable.oxobjectid = $table.oxid and $o2aTable.oxattrid = '{$this->attributeOxid}' and $o2aTable.oxvalue = '{$this->attributeValue}' where $table.oxactive = 1";
    }

}