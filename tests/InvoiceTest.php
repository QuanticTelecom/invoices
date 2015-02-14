<?php

use Mockery as m;
use QuanticTelecom\Invoices\ExcludingTaxInvoice;
use QuanticTelecom\Invoices\IncludingTaxInvoice;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;

class InvoiceTest extends PHPUnit_Framework_TestCase {

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var IncludingTaxInvoice
     */
    private $includingTaxInvoice;
    /**
     * @var ExcludingTaxInvoice
     */
    private $excludingTaxInvoice;

    /**
     * Initialize variables
     */
    function __construct()
    {
        $this->customer = m::mock('QuanticTelecom\Invoices\Contracts\CustomerInterface');
        $this->includingTaxInvoice = new IncludingTaxInvoice($this->customer);
        $this->excludingTaxInvoice = new ExcludingTaxInvoice($this->customer);
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_creates_an_invoice_with_O_total()
    {
        $this->assertEquals(0, $this->includingTaxInvoice->getExcludingTaxTotalPrice());
        $this->assertEquals(0, $this->includingTaxInvoice->getIncludingTaxTotalPrice());

        $this->assertEquals(0, $this->excludingTaxInvoice->getExcludingTaxTotalPrice());
        $this->assertEquals(0, $this->excludingTaxInvoice->getIncludingTaxTotalPrice());
    }

    /**
     * @test
     */
    public function it_returns_the_item_after_added_it()
    {
        $this->includingTaxInvoice->addItem($item = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));
        $this->excludingTaxInvoice->addItem($item = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));

        $this->assertEquals([$item], $this->includingTaxInvoice->getItems());
        $this->assertEquals([$item], $this->excludingTaxInvoice->getItems());
    }
}