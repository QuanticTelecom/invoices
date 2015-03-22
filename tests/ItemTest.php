<?php namespace QuanticTelecom\Invoices\Tests;

use PHPUnit_Framework_TestCase;
use QuanticTelecom\Invoices\Item;

class ItemTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function weGetAllInformationAboutAnItem()
    {
        $name = "name";
        $quantity = 3;
        $includingTaxUnitPrice = 10;
        $excludingTaxUnitPrice = 9;

        $item = new Item(
            $name,
            $quantity,
            $includingTaxUnitPrice,
            $includingTaxUnitPrice * $quantity,
            $excludingTaxUnitPrice,
            $excludingTaxUnitPrice * $quantity
        );

        $this->assertEquals($item->getItemName(), $name);
        $this->assertEquals($item->getItemIncludingTaxUnitPrice(), $includingTaxUnitPrice);
        $this->assertEquals($item->getItemIncludingTaxTotalPrice(), $includingTaxUnitPrice * $quantity);
        $this->assertEquals($item->getItemExcludingTaxUnitPrice(), $excludingTaxUnitPrice);
        $this->assertEquals($item->getItemExcludingTaxTotalPrice(), $excludingTaxUnitPrice * $quantity);
    }
}
