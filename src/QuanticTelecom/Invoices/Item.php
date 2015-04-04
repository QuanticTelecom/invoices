<?php namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\ItemInterface;

/**
 * Class Item
 * @package QuanticTelecom\Invoices
 */
class Item implements ItemInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $includingTaxUnitPrice;

    /**
     * @var float
     */
    private $includingTaxTotalPrice;

    /**
     * @var
     */
    private $excludingTaxUnitPrice;

    /**
     * @var float
     */
    private $excludingTaxTotalPrice;

    /**
     * @param $name
     * @param $quantity
     * @param $includingTaxUnitPrice
     * @param $includingTaxTotalPrice
     * @param $excludingTaxTotalPrice
     */
    public function __construct(
        $name,
        $quantity,
        $includingTaxUnitPrice,
        $includingTaxTotalPrice,
        $excludingTaxUnitPrice,
        $excludingTaxTotalPrice
    ) {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->includingTaxUnitPrice = $includingTaxUnitPrice;
        $this->includingTaxTotalPrice = $includingTaxTotalPrice;
        $this->excludingTaxUnitPrice = $excludingTaxUnitPrice;
        $this->excludingTaxTotalPrice = $excludingTaxTotalPrice;
    }


    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getItemQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getItemIncludingTaxUnitPrice()
    {
        return $this->includingTaxUnitPrice;
    }

    /**
     * @return float
     */
    public function getItemIncludingTaxTotalPrice()
    {
        return $this->includingTaxTotalPrice;
    }

    /**
     * @return float
     */
    public function getItemExcludingTaxUnitPrice()
    {
        return $this->excludingTaxUnitPrice;
    }

    /**
     * @return float
     */
    public function getItemExcludingTaxTotalPrice()
    {
        return $this->excludingTaxTotalPrice;
    }
}
