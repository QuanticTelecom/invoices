<?php

namespace QuanticTelecom\Invoices\Contracts;

use Carbon\Carbon;

interface InvoiceInterface extends GroupsContainerInterface, ItemsContainerInterface
{
    /**
     * Get the ID of the invoice.
     *
     * @return string
     */
    public function getId();

    /**
     * Get the customer.
     *
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * Get the price of all items with all taxes.
     *
     * @return float
     */
    public function getIncludingTaxTotalPrice();

    /**
     * Get the price of all items without taxes.
     *
     * @return float
     */
    public function getExcludingTaxTotalPrice();

    /**
     * Get the amount of VAT of the invoice.
     *
     * @return float
     */
    public function getVatAmount();

    /**
     * Get the due date of the invoice.
     *
     * @return Carbon
     */
    public function getDueDate();

    /**
     * Get the creation date of the invoice.
     *
     * @return Carbon
     */
    public function getCreatedAt();

    /**
     * Get the payment instance of the invoice if paid or null.
     *
     * @return PaymentInterface | null
     */
    public function getPayment();

    /**
     * Set the payment instance for a paid invoice.
     *
     * @param PaymentInterface $payment
     *
     * @return self
     */
    public function setPayment(PaymentInterface $payment);

    /**
     * Check if the payment instance is set.
     *
     * @return bool
     */
    public function isPaid();
}
