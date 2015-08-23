<?php

namespace QuanticTelecom\Invoices\Contracts;

interface GroupOfItemsInterface extends GroupsAndItemsContainerInterface
{
    /**
     * Get the name of the group.
     *
     * @return string
     */
    public function getName();
}
