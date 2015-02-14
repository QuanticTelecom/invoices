<?php namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;

abstract class Invoice {

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var ItemInterface[]
     */
    public $items = [];

    /**
     * @param CustomerInterface $customer
     */
    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Add an item to the invoice
     *
     * @param ItemInterface $item
     * @return $this
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Add an item to the invoice
     *
     * @return ItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return float
     */
    abstract public function getIncludingTaxTotalPrice();

    /**
     * @return float
     */
    abstract public function getExcludingTaxTotalPrice();

    /**
     * @return float
     */
    public function getVatAmount()
    {
        return $this->getIncludingTaxTotalPrice() - $this->getExcludingTaxTotalPrice();
    }
}