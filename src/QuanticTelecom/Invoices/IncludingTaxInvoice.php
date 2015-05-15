<?php

namespace QuanticTelecom\Invoices;

/**
 * Class IncludingTaxInvoice.
 */
class IncludingTaxInvoice extends AbstractInvoice
{
    use IncludingPriceToExcludingPriceTrait;

    /**
     * @return float
     */
    public function getIncludingTaxTotalPrice()
    {
        $includingTaxTotalPrice = 0;

        foreach ($this->getItems() as $item) {
            $includingTaxTotalPrice += $item->getItemIncludingTaxTotalPrice();
        }

        return $includingTaxTotalPrice;
    }

    /**
     * @return float
     */
    public function getExcludingTaxTotalPrice()
    {
        return $this->includingPriceToExcludingPrice($this->getIncludingTaxTotalPrice());
    }
}
