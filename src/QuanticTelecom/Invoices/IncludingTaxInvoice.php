<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\GroupsAndItemsContainerInterface;

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
        return $this->getItemsAndGroupsPrice($this);
    }

    /**
     * @return float
     */
    public function getExcludingTaxTotalPrice()
    {
        return $this->includingPriceToExcludingPrice($this->getIncludingTaxTotalPrice());
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
            $price += $item->getItemIncludingTaxTotalPrice();
        }

        foreach ($groupsAndItemsContainer->getGroups() as $groupOfItems) {
            $price += $this->getItemsAndGroupsPrice($groupOfItems);
        }

        return $price;
    }
}
