<?php

namespace QuanticTelecom\Invoices;

trait IncludingPriceToExcludingPriceTrait
{
    /**
     * Compute excluding tax price from an including tax price.
     *
     * @param $includingTaxPrice
     *
     * @return float
     */
    protected function includingPriceToExcludingPrice($includingTaxPrice)
    {
        return round($includingTaxPrice / (1 + AbstractInvoice::$vatRate), 2);
    }
}
