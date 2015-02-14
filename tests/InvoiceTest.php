<?php

use Mockery as m;
use QuanticTelecom\Invoices\Invoice;

class InvoiceTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_creates_an_invoice_with_O_total()
    {
        $invoice = new Invoice($customer = m::mock('QuanticTelecom\Invoices\Contracts\CustomerInterface'));
        $this->assertEquals(0, $invoice->total());
    }

    /**
     * @test
     */
    public function it_returns_the_item_after_added_it()
    {
        $invoice = new Invoice($customer = m::mock('QuanticTelecom\Invoices\Contracts\CustomerInterface'));

        $invoice->addItem($item = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));

        $this->assertEquals([$item], $invoice->getItems());
    }
}