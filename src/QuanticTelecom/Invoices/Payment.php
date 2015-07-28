<?php namespace QuanticTelecom\Invoices;

use Carbon\Carbon;
use QuanticTelecom\Invoices\Contracts\PaymentInterface;

class Payment implements PaymentInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Carbon
     */
    protected $date;

    /**
     * Set the name of the payment.
     *
     * @param string $name
     */
    public function setPaymentName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the payment.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return $this->name;
    }

    /**
     * Set the date of the payment.
     *
     * @param Carbon $date
     */
    public function setPaymentDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get the date of the payment.
     *
     * @return Carbon
     */
    public function getPaymentDate()
    {
        return $this->date;
    }
}
