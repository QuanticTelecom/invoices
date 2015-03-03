<?php namespace QuanticTelecom\Invoices\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use Carbon\Carbon;
use QuanticTelecom\Invoices\Contracts\IdGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
use QuanticTelecom\Invoices\Contracts\PaymentInterface;
use QuanticTelecom\Invoices\ExcludingTaxInvoice;
use QuanticTelecom\Invoices\IncludingTaxInvoice;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\AbstractInvoice as Invoice;

class InvoiceTest extends PHPUnit_Framework_TestCase
{
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
    public function __construct()
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
    public function itCreatesAnInvoiceWithAnId()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $excludingTaxInvoice = $this->getNewInvoice(ExcludingTaxInvoice::class);

        $this->assertEquals($this->newId, $includingTaxInvoice->getId());
        $this->assertEquals($this->newId, $excludingTaxInvoice->getId());
    }

    /**
     * @test
     */
    public function itCreatesAnInvoiceWithOTotal()
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
    public function itReturnsTheIncludingTaxSumForIncludingTaxInvoice()
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
    public function itReturnsTheExcludingTaxSumForExcludingTaxInvoice()
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

    /**
     * @test
     */
    public function itReturnsTheCreationDateAfterSetting()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $backToTheFuture = Carbon::createFromDate(1955, 11, 5);

        $this->assertNull($includingTaxInvoice->getCreatedAt());

        $includingTaxInvoice->setCreatedAt($backToTheFuture);

        $this->assertEquals($includingTaxInvoice->getCreatedAt(), $backToTheFuture);
    }

    /**
     * @test
     */
    public function itReturnsTheDueDateAfterSetting()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $backToTheFuture = Carbon::createFromDate(1955, 11, 5);

        $this->assertNull($includingTaxInvoice->getCreatedAt());

        $includingTaxInvoice->setDueDate($backToTheFuture);

        $this->assertEquals($includingTaxInvoice->getDueDate(), $backToTheFuture);
    }

    /**
     * @test
     */
    public function itReturnsThePaymentAfterSetting()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $payment = m::mock(PaymentInterface::class);

        $includingTaxInvoice->setPayment($payment);

        $this->assertEquals($includingTaxInvoice->getPayment(), $payment);
    }

    /**
     * @test
     */
    public function theInvoiceIsPaidAfterSettingPayment()
    {
        $includingTaxInvoice = $this->getNewInvoice(IncludingTaxInvoice::class);
        $payment = m::mock(PaymentInterface::class);

        $this->assertFalse($includingTaxInvoice->isPaid());

        $includingTaxInvoice->setPayment($payment);

        $this->assertTrue($includingTaxInvoice->isPaid());
    }
}
