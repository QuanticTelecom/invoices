<?php namespace QuanticTelecom\Invoices\Contracts;

interface GroupOfItemsInterface extends GroupsContainerInterface, ItemsContainerInterface
{
    /**
     * Get the name of the group.
     *
     * @return string
     */
    public function getName();
}
