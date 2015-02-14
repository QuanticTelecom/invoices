<?php namespace QuanticTelecom\Invoices;

class IncludingTaxInvoice extends Invoice {

    /**
     * @return float
     */
    public function getIncludingTaxTotalPrice()
    {
        $includingTaxTotalPrice = 0;

        foreach ($this->getItems() as $item)
        {
            $includingTaxTotalPrice += $item->getItemIncludingTaxTotalPrice();
        }

        return $includingTaxTotalPrice;
    }

    /**
     * @return float
     */
    public function getExcludingTaxTotalPrice()
    {
        return round($this->getIncludingTaxTotalPrice() / (1 + Invoice::$vatRate), 2);
    }
}