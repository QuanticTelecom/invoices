<?php namespace QuanticTelecom\Invoices\Contracts;

use Carbon\Carbon;

interface PaymentInterface
{
    /**
     * @return string
     */
    public function getPaymentName();

    /**
     * @return Carbon
     */
    public function getPaymentDate();
}
