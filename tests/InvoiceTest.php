<?php

use Mockery as m;
use QuanticTelecom\Invoices\Contracts\IdGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
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
     * @var IdGeneratorInterface
     */
    private $idGenerator;

    /**
     * ID returned by the IdGeneratorInterface mock
     *
     * @var string
     */
    private $newId;

    /**
     * Initialize variables
     */
    function __construct()
    {
        $this->newId = '2015-02-14-0001';

        $this->customer = m::mock(CustomerInterface::class);
        $this->idGenerator = m::mock(IdGeneratorInterface::class);
        $this->idGenerator->shouldReceive('generateNewId')->andReturn($this->newId);
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @param string $class An implementation of the Invoice class
     * @return Invoice
     */
    private function getNewInvoice($class)
    {
        return new $class($this->idGenerator, $this->customer);
    }

    /**
     * @test
     */
    public function it_creates_an_invoice_with_an_id()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $excludingTaxInvoice = $this->getNewInvoice(ExcludingTaxInvoice::class);

        $this->assertEquals($this->newId, $includingTaxInvoice->getId());
        $this->assertEquals($this->newId, $excludingTaxInvoice->getId());
    }

    /**
     * @test
     */
    public function it_creates_an_invoice_with_O_total()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $excludingTaxInvoice = $this->getNewInvoice(ExcludingTaxInvoice::class);

        $this->assertEquals(0, $includingTaxInvoice->getExcludingTaxTotalPrice());
        $this->assertEquals(0, $includingTaxInvoice->getIncludingTaxTotalPrice());

        $this->assertEquals(0, $excludingTaxInvoice->getExcludingTaxTotalPrice());
        $this->assertEquals(0, $excludingTaxInvoice->getIncludingTaxTotalPrice());
    }

    /**
     * @test
     */
    public function it_returns_the_item_after_added_it()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $excludingTaxInvoice = $this->getNewInvoice(ExcludingTaxInvoice::class);

        $item = m::mock(ItemInterface::class);

        $includingTaxInvoice->addItem($item);
        $excludingTaxInvoice->addItem($item);

        $this->assertEquals([$item], $includingTaxInvoice->getItems());
        $this->assertEquals([$item], $excludingTaxInvoice->getItems());
    }

    /**
     * @test
     */
    public function it_returns_the_including_tax_sum_for_including_tax_invoice()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);

        $item1Price = 10.00;
        $item2Price = 8.00;

        $includingTaxInvoice->addItem($item1 = m::mock(ItemInterface::class));
        $includingTaxInvoice->addItem($item2 = m::mock(ItemInterface::class));
        $item1->shouldReceive('getItemIncludingTaxTotalPrice')->andReturn($item1Price);
        $item2->shouldReceive('getItemIncludingTaxTotalPrice')->andReturn($item2Price);

        $includingTaxTotalPrice = $item1Price + $item2Price;
        $excludingTaxTotalPrice = round(($item1Price + $item2Price) / (1 + Invoice::$vatRate), 2);

        $this->assertEquals($includingTaxTotalPrice, $includingTaxInvoice->getIncludingTaxTotalPrice());
        $this->assertEquals($excludingTaxTotalPrice, $includingTaxInvoice->getExcludingTaxTotalPrice());
    }

    /**
     * @test
     */
    public function it_returns_the_excluding_tax_sum_for_excluding_tax_invoice()
    {
        $excludingTaxInvoice = $this->getNewInvoice(ExcludingTaxInvoice::class);

        $item1Price = 10.00;
        $item2Price = 8.00;

        $excludingTaxInvoice->addItem($item1 = m::mock(ItemInterface::class));
        $excludingTaxInvoice->addItem($item2 = m::mock(ItemInterface::class));
        $item1->shouldReceive('getItemExcludingTaxTotalPrice')->andReturn($item1Price);
        $item2->shouldReceive('getItemExcludingTaxTotalPrice')->andReturn($item2Price);

        $includingTaxTotalPrice = round(($item1Price + $item2Price) * (1 + Invoice::$vatRate), 2);
        $excludingTaxTotalPrice = $item1Price + $item2Price;

        $this->assertEquals($excludingTaxTotalPrice, $excludingTaxInvoice->getExcludingTaxTotalPrice());
        $this->assertEquals($includingTaxTotalPrice, $excludingTaxInvoice->getIncludingTaxTotalPrice());
    }
}