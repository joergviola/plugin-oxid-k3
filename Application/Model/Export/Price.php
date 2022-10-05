<?php

namespace ObjectCode\K3\Application\Model\Export;

class Price
{
    /**
     * @var float
     */
    protected float $price = 0.0;

    /**
     * @var string
     */
    protected string $currency = '';

    /**
     * @var string
     */
    protected string $unit = '';

    /**
     * @var int
     */
    protected int $sort = 0;

    /**
     * @var string
     */
    protected string $displayOnly = '';

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $fromDate = null;

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $toDate = null;

    /**
     * @var int
     */
    protected int $fromQty = 0;

    /**
     * @var int
     */
    protected int $toQty = 0;

    /**
     * @var string
     */
    protected string $type = '';

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getDisplayOnly(): string
    {
        return $this->displayOnly;
    }

    /**
     * @param string $displayOnly
     */
    public function setDisplayOnly(string $displayOnly): void
    {
        $this->displayOnly = $displayOnly;
    }

    /**
     * @return \DateTime|null
     */
    public function getFromDate(): ?\DateTime
    {
        return $this->fromDate;
    }

    /**
     * @param \DateTime|null $fromDate
     */
    public function setFromDate(?\DateTime $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getToDate(): ?\DateTime
    {
        return $this->toDate;
    }

    /**
     * @param \DateTime|null $toDate
     */
    public function setToDate(?\DateTime $toDate): void
    {
        $this->toDate = $toDate;
    }

    /**
     * @return int
     */
    public function getFromQty(): int
    {
        return $this->fromQty;
    }

    /**
     * @param int $fromQty
     */
    public function setFromQty(int $fromQty): void
    {
        $this->fromQty = $fromQty;
    }

    /**
     * @return int
     */
    public function getToQty(): int
    {
        return $this->toQty;
    }

    /**
     * @param int $toQty
     */
    public function setToQty(int $toQty): void
    {
        $this->toQty = $toQty;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * fixed
     * surcharge
     * surchargeHidden
     *
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Return array
     *
     * @return array
     */
    public function getArray(): array
    {
        $vars = get_object_vars($this);

        if ( $vars['fromQty'] === 0 ) {
            $vars['fromQty'] = null;
        }

        if ( $vars['toQty'] === 0 ) {
            $vars['toQty'] = null;
        }

        return $vars;
    }

}