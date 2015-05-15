<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\GroupOfItemsInterface;

/**
 * Class GroupOfItems.
 */
class GroupOfItems implements GroupOfItemsInterface
{
    use ItemsContainerTrait;
    use GroupsContainerTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @param $name string Name of the group.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the group.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
