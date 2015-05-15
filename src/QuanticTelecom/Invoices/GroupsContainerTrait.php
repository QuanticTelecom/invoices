<?php

namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\GroupOfItemsInterface;

/**
 * Class GroupsContainerTrait.
 */
trait GroupsContainerTrait
{
    /**
     * @var GroupOfItemsInterface[]
     */
    private $groups = [];

    /**
     * Get the groups of the container.
     *
     * @return GroupOfItemsInterface[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add a group in the container.
     *
     * @param GroupOfItemsInterface $group
     *
     * @return $this
     */
    public function addGroup(GroupOfItemsInterface $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Create a new group and add it in the container.
     *
     * @param string $name
     * @param string $class GroupOfItemsInterface implementation
     *
     * @return GroupOfItemsInterface the new group created
     */
    public function createAndAddGroup($name, $class = GroupOfItems::class)
    {
        $group = new $class($name);

        $this->groups[] = $group;

        return $group;
    }
}
