<?php

namespace FATCHIP\K3\Application\Model\Export;

class Product
{
    /**
     * Product number
     *
     * @var string
     */
    protected string $no = '';

    /**
     * Name
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Description
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Category
     *
     * @var string
     */
    protected string $category = '';

    /**
     * Prices
     *
     * @var array
     */
    protected array $prices = [];

    /**
     * @return string
     */
    public function getNo(): string
    {
        return $this->no;
    }

    /**
     * @param string $no
     */
    public function setNo(string $no): void
    {
        $this->no = $no;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param array $prices
     */
    public function setPrices(array $prices): void
    {
        $this->prices = $prices;
    }

    /**
     * @param array $price
     */
    public function setPrice(array $price): void
    {
        $this->prices[] = $price;
    }

    /**
     * Return object as array
     *
     * @return array
     */
    public function getArray(): array
    {
        $prices = $this->getPrices();
        $priceArray = [];
        foreach ($prices as $price) {
            $priceArray[] = $price->getArray();
        }
        $vars = get_object_vars($this);
        $vars['prices'] = $priceArray;
        return $vars;
    }

}