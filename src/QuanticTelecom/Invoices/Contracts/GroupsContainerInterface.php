<?php namespace QuanticTelecom\Invoices\Contracts;

interface GroupsContainerInterface
{
    /**
     * Get the groups of the container.
     *
     * @return GroupOfItemsInterface[]
     */
    public function getGroups();

    /**
     * Add a group in the container.
     *
     * @param GroupOfItemsInterface $group
     * @return $this
     */
    public function addGroup(GroupOfItemsInterface $group);

    /**
     * Create a new group and add it in the container.
     *
     * @param string $name
     * @return GroupOfItemsInterface the new group created
     */
    public function createAndAddGroup($name);
}
