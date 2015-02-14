<?php namespace QuanticTelecom\Invoices;

class ExcludingTaxInvoice extends Invoice {

    /**
     * @return float
     */
    public function getIncludingTaxTotalPrice()
    {
        return 0;
    }

    /**
     * @return float
     */
    public function getExcludingTaxTotalPrice()
    {
        return 0;
    }
}