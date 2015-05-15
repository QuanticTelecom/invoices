<?php

namespace QuanticTelecom\Invoices;

trait ExcludingPriceToIncludingPriceTrait
{
    /**
     * Compute including tax price from an excluding tax price.
     *
     * @param $excludingTaxPrice
     *
     * @return float
     */
    protected function excludingPriceToIncludingPrice($excludingTaxPrice)
    {
        return round($excludingTaxPrice * (1 + AbstractInvoice::$vatRate), 2);
    }
}
