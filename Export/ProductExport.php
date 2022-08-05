<?php

namespace FATCHIP\K3\Core\Export;

use Doctrine\DBAL\FetchMode;
use FATCHIP\K3\Core\Export\Model\Price;
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
     * Currency
     *
     * @var null
     */
    protected string $currency = '';

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
     * Set currency
     *
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Return currency
     *
     * @return string
     */
    protected function getCurrency(): string
    {
        return $this->currency;
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

    /**
     * Return export data
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getData(): array
    {
        $products = $this->getExportProducts();
        foreach ($products as $product) {
            $this->data[] = $product->getArray();
        }
        return $this->data;
    }

    /**
     * Return export products
     *
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function getExportProducts(): array
    {
        $products = [];
        $query = $this->getProductsQuery();
        $result = DatabaseProvider::getDb()->getAll($query);
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                $products[] = $this->getExportProduct($row[0]);
            }
        }
        return $products;
    }

    /**
     * Return export product
     *
     * @param $id
     * @return Product|mixed|void
     */
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
            return $exportProduct;
        }
    }

    /**
     * Return product prices
     *
     * @param $article
     * @return array
     */
    protected function getExportProductPrices($article): array
    {
        $prices = [];
        $prices[] = $this->getExportProductPrice($article);
        $exportTPrice = $this->getExportProductTPrice($article);
        if ($exportTPrice) {
            $prices[] = $exportTPrice;
        }
        $amountPrices = $this->getExportProductAmountPrices($article);
        if ($amountPrices && count($amountPrices) > 0) {
            foreach ($amountPrices as $amountPrice) {
                $prices[] = $amountPrice;
            }
        }
        return $prices;
    }

    /**
     * Return product price
     *
     * @param $article
     * @return Price|mixed
     */
    protected function getExportProductPrice($article)
    {
        $price = $article->getPrice();
        $exportPrice = oxNew(Price::class);
        $exportPrice->setPrice($price->getBruttoPrice());
        $exportPrice->setType('fixed');
        $exportPrice->setCurrency($this->getCurrency());
        return $exportPrice;
    }

    /**
     * Return strike through price
     *
     * @param $article
     * @return Price|mixed|void
     */
    protected function getExportProductTPrice($article)
    {
        $price = $article->getTPrice();
        if ($price) {
            $exportPrice = oxNew(Price::class);
            $exportPrice->setPrice($price->getBruttoPrice());
            $exportPrice->setType('fixed');
            $exportPrice->setDisplayOnly('streich');
            $exportPrice->setCurrency($this->getCurrency());
            return $exportPrice;
        }
    }

    /**
     * Return amount prices
     *
     * @param $article
     * @return array
     */
    protected function getExportProductAmountPrices($article): array
    {
        $amountPrices = $article->loadAmountPriceInfo();
        if ($amountPrices) {
            $exportPrices = [];
            foreach ($amountPrices as $amountPrice) {
                $price = str_replace('.', '', $amountPrice->fbrutprice);
                $price = str_replace(',', '.', $price);
                $exportPrice = oxNew(Price::class);
                $exportPrice->setPrice($price);
                $exportPrice->setType('fixed');
                $exportPrice->setFromQty($amountPrice->getFieldData('oxamount'));
                $exportPrice->setToQty($amountPrice->getFieldData('oxamountto'));
                $exportPrice->setCurrency($this->getCurrency());
                $exportPrices[] = $exportPrice;
            }
            return $exportPrices;
        }
        return [];
    }

    /**
     * Return product query
     *
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    protected function getProductsQuery(): string
    {
        $o2aTable = $this->getOxObject2AttributeTable();
        $query = "select count(oxid) from $o2aTable where $o2aTable.oxattrid = '{$this->attributeOxid}' limit 1";
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
        return "select $table.oxid from $table join $o2aTable on $o2aTable.oxobjectid = $table.oxid and $o2aTable.oxattrid = '{$this->attributeOxid}' where $table.oxactive = 1";
    }

}