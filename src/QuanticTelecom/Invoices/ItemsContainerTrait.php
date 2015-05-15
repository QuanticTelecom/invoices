<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\ItemInterface;

/**
 * Class ItemsContainerTrait.
 */
trait ItemsContainerTrait
{
    /**
     * @var ItemInterface[]
     */
    private $items = [];

    /**
     * Get the items of the container.
     *
     * @return ItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add an item in the container.
     *
     * @param ItemInterface $item
     *
     * @return $this
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;

        return $this;
    }
}
