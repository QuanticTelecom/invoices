<?php namespace QuanticTelecom\Invoices\Contracts;

interface CustomerInterface {

    /**
     * Get the ID that should be shown on the customer's invoices.
     *
     * @return string
     */
    public function getCustomerId();

    /**
     * Get the name that should be shown on the customer's invoices.
     *
     * @return string
     */
    public function getCustomerName();

    /**
     * Get the address that should be shown on the customer's invoices.
     * One line string address or multiple lines array of string address
     *
     * @return string | array
     */
    public function getCustomerAddress();
}