<?php

namespace QuanticTelecom\Invoices\Contracts;

use Carbon\Carbon;

interface PaymentInterface
{
    /**
     * Get the name of the payment.
     *
     * @return string
     */
    public function getPaymentName();

    /**
     * Get the date of the payment.
     *
     * @return Carbon
     */
    public function getPaymentDate();
}
