<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\GroupsAndItemsContainerInterface;

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
        return $this->getItemsAndGroupsPrice($this);
    }


    /**
     * Compute recursively the price for all the items and all the groups.
     *
     * @param GroupsAndItemsContainerInterface $groupsAndItemsContainer
     *
     * @return float
     */
    private function getItemsAndGroupsPrice(
        GroupsAndItemsContainerInterface $groupsAndItemsContainer
    ) {
        $price = 0;

        foreach ($groupsAndItemsContainer->getItems() as $item) {
            $price += $item->getItemExcludingTaxTotalPrice();
        }

        foreach ($groupsAndItemsContainer->getGroups() as $groupOfItems) {
            $price += $this->getItemsAndGroupsPrice($groupOfItems);
        }

        return $price;
    }
}
