<?php

namespace QuanticTelecom\Invoices\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
use QuanticTelecom\Invoices\ItemsContainerTrait;

class ItemsContainerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @return ItemsContainerTrait
     */
    private function getNewItemsContainer()
    {
        return $this->getObjectForTrait(ItemsContainerTrait::class);
    }

    /**
     * @test
     */
    public function weCanGetTheItemAfterAddedIt()
    {
        $itemsContainer = $this->getNewItemsContainer();

        $item = m::mock(ItemInterface::class);

        $itemsContainer->addItem($item);

        $this->assertEquals([$item], $itemsContainer->getItems());
    }
}
