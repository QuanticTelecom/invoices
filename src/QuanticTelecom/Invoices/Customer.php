<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\CustomerInterface;

class Customer implements CustomerInterface
{
    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $customerName;

    /**
     * @var string | array
     */
    protected $customerAddress;

    /**
     * Set the ID that should be shown on the customer's invoices.
     *
     * @param string $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * Set the name that should be shown on the customer's invoices.
     *
     * @param string $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * Set the address that should be shown on the customer's invoices.
     * One line string address or multiple lines array of string address.
     *
     * @param string | array $customerAddress
     */
    public function setCustomerAddress($customerAddress)
    {
        $this->customerAddress = $customerAddress;
    }

    /**
     * Get the ID that should be shown on the customer's invoices.
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Get the name that should be shown on the customer's invoices.
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Get the address that should be shown on the customer's invoices.
     * One line string address or multiple lines array of string address.
     *
     * @return string | array
     */
    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }
}
