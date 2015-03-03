<?php namespace QuanticTelecom\Invoices;

class ExcludingTaxInvoice extends AbstractInvoice
{
    /**
     * @return float
     */
    public function getIncludingTaxTotalPrice()
    {
        return round($this->getExcludingTaxTotalPrice() * (1 + AbstractInvoice::$vatRate), 2);
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
