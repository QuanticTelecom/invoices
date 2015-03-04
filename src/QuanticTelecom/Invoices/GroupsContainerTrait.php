<?php namespace QuanticTelecom\Invoices;

trait GroupsContainerTrait
{
    /**
     * @var GroupOfItems[]
     */
    private $groups = [];

    /**
     * Get the groups of the container.
     *
     * @return GroupOfItems[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add a group in the container.
     *
     * @param GroupOfItems $group
     * @return $this
     */
    public function addGroup(GroupOfItems $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Create a new group and add it in the container.
     *
     * @param string $name
     * @return GroupOfItems the new group created
     */
    public function createAndAddGroup($name)
    {
        $group = new GroupOfItems($name);

        $this->groups[] = $group;

        return $group;
    }
}
