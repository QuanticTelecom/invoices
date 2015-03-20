<?php namespace QuanticTelecom\Invoices\Contracts;

use Carbon\Carbon;

interface InvoiceInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * @return float
     */
    public function getIncludingTaxTotalPrice();

    /**
     * @return float
     */
    public function getExcludingTaxTotalPrice();

    /**
     * @return float
     */
    public function getVatAmount();

    /**
     * @return Carbon
     */
    public function getDueDate();

    /**
     * @param Carbon $dueDate | null
     * @return self
     */
    public function setDueDate(Carbon $dueDate = null);

    /**
     * @return Carbon
     */
    public function getCreatedAt();

    /**
     * @param Carbon $createdAt | null
     * @return self
     */
    public function setCreatedAt(Carbon $createdAt = null);

    /**
     * @return PaymentInterface
     */
    public function getPayment();

    /**
     * @param PaymentInterface $payment
     * @return self
     */
    public function setPayment(PaymentInterface $payment);

    /**
     * @return bool
     */
    public function isPaid();
}
