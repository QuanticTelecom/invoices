<?php namespace QuanticTelecom\Invoices\Contracts; 

interface ItemInterface {

    /**
     * @return string
     */
    public function getItemName();

    /**
     * @return int
     */
    public function getItemQuantity();

    /**
     * @return float
     */
    public function getItemIncludingTaxUnitPrice();

    /**
     * @return float
     */
    public function getItemIncludingTaxTotalPrice();

    /**
     * @return float
     */
    public function getItemExcludingTaxUnitPrice();

    /**
     * @return float
     */
    public function getItemExcludingTaxTotalPrice();
}