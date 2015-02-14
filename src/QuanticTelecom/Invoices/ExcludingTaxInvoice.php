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
        $excludingTaxTotalPrice = 0;

        foreach ($this->getItems() as $item)
        {
            $excludingTaxTotalPrice += $item->getItemExcludingTaxTotalPrice();
        }

        return $excludingTaxTotalPrice;
    }
}