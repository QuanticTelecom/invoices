<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\ItemInterface;

/**
 * Class Item.
 */
class Item implements ItemInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $includingTaxUnitPrice;

    /**
     * @var float
     */
    protected $includingTaxTotalPrice;

    /**
     * @var float
     */
    protected $excludingTaxUnitPrice;

    /**
     * @var float
     */
    protected $excludingTaxTotalPrice;

    /**
     * @param string $name
     * @param int    $quantity
     * @param float  $includingTaxUnitPrice
     * @param float  $includingTaxTotalPrice
     * @param float  $excludingTaxUnitPrice
     * @param float  $excludingTaxTotalPrice
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
     * Get the item name.
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->name;
    }

    /**
     * Get the item quantity.
     *
     * @return int
     */
    public function getItemQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get the unit price of the item with taxes.
     *
     * @return float
     */
    public function getItemIncludingTaxUnitPrice()
    {
        return $this->includingTaxUnitPrice;
    }

    /**
     * Get the total price of the item with taxes.
     *
     * @return float
     */
    public function getItemIncludingTaxTotalPrice()
    {
        return $this->includingTaxTotalPrice;
    }

    /**
     * Get the unit price of the item without taxes.
     *
     * @return float
     */
    public function getItemExcludingTaxUnitPrice()
    {
        return $this->excludingTaxUnitPrice;
    }

    /**
     * Get the total price of the item without taxes.
     *
     * @return float
     */
    public function getItemExcludingTaxTotalPrice()
    {
        return $this->excludingTaxTotalPrice;
    }
}
