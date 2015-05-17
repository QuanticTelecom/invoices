<?php

namespace QuanticTelecom\Invoices;

class IncludingTaxItem extends Item
{
    use IncludingPriceToExcludingPriceTrait;

    /**
     * @param string $name
     * @param int    $quantity
     * @param float  $includingTaxUnitPrice
     */
    public function __construct(
        $name,
        $quantity,
        $includingTaxUnitPrice
    ) {
        $includingTaxTotalPrice = $quantity * $includingTaxUnitPrice;

        $this->name = $name;
        $this->quantity = $quantity;
        $this->includingTaxUnitPrice = $includingTaxUnitPrice;
        $this->includingTaxTotalPrice = $includingTaxTotalPrice;
        $this->excludingTaxUnitPrice = $this->includingPriceToExcludingPrice($includingTaxUnitPrice);
        $this->excludingTaxTotalPrice = $this->includingPriceToExcludingPrice($includingTaxTotalPrice);
    }
}
