<?php

use Mockery as m;
use QuanticTelecom\Invoices\ExcludingTaxInvoice;
use QuanticTelecom\Invoices\IncludingTaxInvoice;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\Invoice;

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
        $this->newId1 = '2015-02-14-0001';
        $this->newId2 = '2015-02-14-0002';

        $this->customer = m::mock('QuanticTelecom\Invoices\Contracts\CustomerInterface');
        $this->idGenerator = m::mock('QuanticTelecom\Invoices\Contracts\IdGeneratorInterface');
        $this->idGenerator->shouldReceive('generateNewId')->times(2)->andReturn($this->newId1, $this->newId2);

        $this->includingTaxInvoice = new IncludingTaxInvoice($this->idGenerator, $this->customer);
        $this->excludingTaxInvoice = new ExcludingTaxInvoice($this->idGenerator, $this->customer);
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_creates_an_invoice_with_an_id()
    {
        $this->assertEquals($this->newId1, $this->includingTaxInvoice->getId());

        $this->assertEquals($this->newId2, $this->excludingTaxInvoice->getId());
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

    /**
     * @test
     */
    public function it_returns_the_including_tax_sum_for_including_tax_invoice()
    {
        $item1Price = 10.00;
        $item2Price = 8.00;

        $this->includingTaxInvoice->addItem($item1 = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));
        $this->includingTaxInvoice->addItem($item2 = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));
        $item1->shouldReceive('getItemIncludingTaxTotalPrice')->andReturn($item1Price);
        $item2->shouldReceive('getItemIncludingTaxTotalPrice')->andReturn($item2Price);

        $includingTaxTotalPrice = $item1Price + $item2Price;
        $excludingTaxTotalPrice = round(($item1Price + $item2Price) / (1 + Invoice::$vatRate), 2);

        $this->assertEquals($includingTaxTotalPrice, $this->includingTaxInvoice->getIncludingTaxTotalPrice());
        $this->assertEquals($excludingTaxTotalPrice, $this->includingTaxInvoice->getExcludingTaxTotalPrice());
    }

    /**
     * @test
     */
    public function it_returns_the_excluding_tax_sum_for_excluding_tax_invoice()
    {
        $item1Price = 10.00;
        $item2Price = 8.00;

        $this->excludingTaxInvoice->addItem($item1 = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));
        $this->excludingTaxInvoice->addItem($item2 = m::mock('QuanticTelecom\Invoices\Contracts\ItemInterface'));
        $item1->shouldReceive('getItemExcludingTaxTotalPrice')->andReturn($item1Price);
        $item2->shouldReceive('getItemExcludingTaxTotalPrice')->andReturn($item2Price);

        $includingTaxTotalPrice = round(($item1Price + $item2Price) * (1 + Invoice::$vatRate), 2);
        $excludingTaxTotalPrice = $item1Price + $item2Price;

        $this->assertEquals($excludingTaxTotalPrice, $this->excludingTaxInvoice->getExcludingTaxTotalPrice());
        $this->assertEquals($includingTaxTotalPrice, $this->excludingTaxInvoice->getIncludingTaxTotalPrice());
    }
}