<?php namespace QuanticTelecom\Invoices\Contracts;

use Carbon\Carbon;

interface Payment
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
