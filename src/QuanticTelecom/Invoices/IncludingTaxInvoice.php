<?php namespace QuanticTelecom\Invoices;

class IncludingTaxInvoice extends Invoice {

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