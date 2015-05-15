<?php

namespace QuanticTelecom\Invoices\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use QuanticTelecom\Invoices\GroupOfItems;

class GroupOfItemsTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function itCreatesAGroupOfItemsWithAName()
    {
        $name = 'FooBar';
        $groupOfItems = new GroupOfItems($name);

        $this->assertEquals($groupOfItems->getName(), $name);
    }
}
