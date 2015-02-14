<?php namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\CustomerInterface;

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

    public function total()
    {
        return 0;
    }
}