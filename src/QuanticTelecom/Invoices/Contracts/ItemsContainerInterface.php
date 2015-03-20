<?php namespace QuanticTelecom\Invoices\Contracts;

interface ItemsContainerInterface
{
    /**
     * Get the items of the container.
     *
     * @return ItemInterface[]
     */
    public function getItems();

    /**
     * Add an item in the container.
     *
     * @param ItemInterface $item
     * @return $this
     */
    public function addItem(ItemInterface $item);
}
