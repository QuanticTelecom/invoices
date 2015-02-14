<?php namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;

class Invoice {

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var array
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

    public function getItems()
    {
        return $this->items;
    }

    /**
     * Return total
     *
     * @return float
     */
    public function total()
    {
        return 0;
    }
}