<?php

namespace QuanticTelecom\Invoices;

/**
 * Class ExcludingTaxInvoice.
 */
class ExcludingTaxInvoice extends AbstractInvoice
{
    use ExcludingPriceToIncludingPriceTrait;

    /**
     * @return float
     */
    public function getIncludingTaxTotalPrice()
    {
        return $this->excludingPriceToIncludingPrice($this->getExcludingTaxTotalPrice());
    }

    /**
     * @return float
     */
    public function getExcludingTaxTotalPrice()
    {
        $excludingTaxTotalPrice = 0;

        foreach ($this->getItems() as $item) {
            $excludingTaxTotalPrice += $item->getItemExcludingTaxTotalPrice();
        }

        return $excludingTaxTotalPrice;
    }
}
