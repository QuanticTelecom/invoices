<?php namespace QuanticTelecom\Invoices\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
use QuanticTelecom\Invoices\GroupOfItems;
use QuanticTelecom\Invoices\GroupsContainerTrait;

class GroupsContainerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @return GroupsContainerTrait
     */
    private function getNewGroupsContainer()
    {
        return $this->getObjectForTrait(GroupsContainerTrait::class);
    }

    /**
     * @test
     */
    public function weCanGetTheGroupAfterAddedIt()
    {
        $groupsContainer = $this->getNewGroupsContainer();

        $group = m::mock(GroupOfItems::class);

        $groupsContainer->addGroup($group);

        $this->assertEquals([$group], $groupsContainer->getGroups());
    }

    /**
     * @test
     */
    public function itReturnsTheGroupAfterCreatedAndAddedIt()
    {
        $name = 'FooBar';
        $groupsContainer = $this->getNewGroupsContainer();

        $group = $groupsContainer->createAndAddGroup($name);

        $this->assertInstanceOf(GroupOfItems::class, $group);
        $this->assertEquals($group->getName(), $name);
        $this->assertEquals([$group], $groupsContainer->getGroups());
    }
}
