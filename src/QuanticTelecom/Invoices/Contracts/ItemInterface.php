<?php

namespace QuanticTelecom\Invoices\Contracts;

interface ItemInterface
{
    /**
     * Get the item name.
     *
     * @return string
     */
    public function getItemName();

    /**
     * Get the item quantity.
     *
     * @return int
     */
    public function getItemQuantity();

    /**
     * Get the unit price of the item with taxes.
     *
     * @return float
     */
    public function getItemIncludingTaxUnitPrice();

    /**
     * Get the total price of the item with taxes.
     *
     * @return float
     */
    public function getItemIncludingTaxTotalPrice();

    /**
     * Get the unit price of the item without taxes.
     *
     * @return float
     */
    public function getItemExcludingTaxUnitPrice();

    /**
     * Get the total price of the item without taxes.
     *
     * @return float
     */
    public function getItemExcludingTaxTotalPrice();
}
