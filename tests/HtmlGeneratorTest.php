<?php namespace QuanticTelecom\Invoices\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use PHPUnit_Framework_TestCase;
use Mockery as m;
use Carbon\Carbon;
use QuanticTelecom\Invoices\Contracts\IdGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
use QuanticTelecom\Invoices\Contracts\PaymentInterface;
use QuanticTelecom\Invoices\ExcludingTaxInvoice;
use QuanticTelecom\Invoices\GroupOfItems;
use QuanticTelecom\Invoices\HtmlGenerator;
use QuanticTelecom\Invoices\IncludingTaxInvoice;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\AbstractInvoice as Invoice;

class HtmlGeneratorTest extends PHPUnit_Framework_TestCase
{
    private $invoiceData = [
        'id' => '2015-03-04-0042',
        'createdAt' => '1955-11-5',
        'dueDate' => '2015-10-21',
        'excludingTaxTotalPrice' => 8086,
        'includingTaxTotalPrice' => 10120,
        'vatAmount' => 2034
    ];

    private $customerData = [
        'id' => '1337',
        'name' => 'Sauron',
        'address' => 'Black Gate of Mordor'
    ];

    private $itemsData = [
        'ring' => [
            'name' => 'One ring',
            'quantity' => 1,
            'excludingTaxUnitPrice' => 8000,
            'excludingTaxTotalPrice' => 8000,
            'includingTaxTotalPrice' => 10000,
        ]
    ];

    private $groupsData = [
        'stuff' => [
            'name' => 'Killer stuff',
            'items' => [
                'gloves' => [
                    'name' => 'Gloves',
                    'quantity' => 2,
                    'excludingTaxUnitPrice' => 8,
                    'excludingTaxTotalPrice' => 16,
                    'includingTaxTotalPrice' => 20,
                ],
                'armor' => [
                    'name' => 'Spiky plate armor',
                    'quantity' => 1,
                    'excludingTaxUnitPrice' => 70,
                    'excludingTaxTotalPrice' => 70,
                    'includingTaxTotalPrice' => 100,
                ]
            ]
        ]
    ];

    private $payment = [
        'name' => 'gold',
        'date' => '2015-10-20'
    ];

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = new Filesystem();
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @return Invoice mock
     */
    private function getNewInvoice()
    {
        $sauron = m::mock(CustomerInterface::class);
        $sauron->shouldReceive('getCustomerId')->andReturn($this->customerData['id']);
        $sauron->shouldReceive('getCustomerName')->andReturn($this->customerData['name']);
        $sauron->shouldReceive('getCustomerAddress')->andReturn($this->customerData['address']);

        $ring = m::mock(ItemInterface::class);
        $ring->shouldReceive('getItemName')
            ->andReturn($this->itemsData['ring']['name']);
        $ring->shouldReceive('getItemQuantity')
            ->andReturn($this->itemsData['ring']['quantity']);
        $ring->shouldReceive('getItemExcludingTaxUnitPrice')
            ->andReturn($this->itemsData['ring']['excludingTaxUnitPrice']);
        $ring->shouldReceive('getItemExcludingTaxTotalPrice')
            ->andReturn($this->itemsData['ring']['excludingTaxTotalPrice']);
        $ring->shouldReceive('getItemIncludingTaxTotalPrice')
            ->andReturn($this->itemsData['ring']['includingTaxTotalPrice']);

        $gloves = m::mock(ItemInterface::class);
        $gloves->shouldReceive('getItemName')
            ->andReturn($this->groupsData['stuff']['items']['gloves']['name']);
        $gloves->shouldReceive('getItemQuantity')
            ->andReturn($this->groupsData['stuff']['items']['gloves']['quantity']);
        $gloves->shouldReceive('getItemExcludingTaxUnitPrice')
            ->andReturn($this->groupsData['stuff']['items']['gloves']['excludingTaxUnitPrice']);
        $gloves->shouldReceive('getItemExcludingTaxTotalPrice')
            ->andReturn($this->groupsData['stuff']['items']['gloves']['excludingTaxTotalPrice']);
        $gloves->shouldReceive('getItemIncludingTaxTotalPrice')
            ->andReturn($this->groupsData['stuff']['items']['gloves']['includingTaxTotalPrice']);

        $armor = m::mock(ItemInterface::class);
        $armor->shouldReceive('getItemName')
            ->andReturn($this->groupsData['stuff']['items']['armor']['name']);
        $armor->shouldReceive('getItemQuantity')
            ->andReturn($this->groupsData['stuff']['items']['armor']['quantity']);
        $armor->shouldReceive('getItemExcludingTaxUnitPrice')
            ->andReturn($this->groupsData['stuff']['items']['armor']['excludingTaxUnitPrice']);
        $armor->shouldReceive('getItemExcludingTaxTotalPrice')
            ->andReturn($this->groupsData['stuff']['items']['armor']['excludingTaxTotalPrice']);
        $armor->shouldReceive('getItemIncludingTaxTotalPrice')
            ->andReturn($this->groupsData['stuff']['items']['armor']['includingTaxTotalPrice']);

        $stuff = m::mock(GroupOfItems::class);
        $stuff->shouldReceive('getName')->andReturn($this->groupsData['stuff']['name']);
        $stuff->shouldReceive('getItems')->andReturn([$gloves, $armor]);
        $stuff->shouldReceive('getGroups')->andReturn([]);

        $paymentDate = Carbon::createFromFormat('Y-m-j', $this->payment['date']);
        $payment = m::mock(PaymentInterface::class);
        $payment->shouldReceive('getPaymentName')->andReturn($this->payment['name']);
        $payment->shouldReceive('getPaymentDate')->andReturn($paymentDate);

        $backToTheFutureCreation = Carbon::createFromFormat('Y-m-j', $this->invoiceData['createdAt']);
        $backToTheFutureDueDate = Carbon::createFromFormat('Y-m-j', $this->invoiceData['dueDate']);
        $invoice = m::mock(Invoice::class);
        $invoice->shouldReceive('getId')
            ->andReturn($this->invoiceData['id']);
        $invoice->shouldReceive('getCustomer')
            ->andReturn($sauron);
        $invoice->shouldReceive('getCreatedAt')
            ->andReturn($backToTheFutureCreation);
        $invoice->shouldReceive('getDueDate')
            ->andReturn($backToTheFutureDueDate);
        $invoice->shouldReceive('getItems')
            ->andReturn([$ring]);
        $invoice->shouldReceive('getGroups')
            ->andReturn([$stuff]);
        $invoice->shouldReceive('getExcludingTaxTotalPrice')
            ->andReturn($this->invoiceData['excludingTaxTotalPrice']);
        $invoice->shouldReceive('getVatAmount')
            ->andReturn($this->invoiceData['vatAmount']);
        $invoice->shouldReceive('getIncludingTaxTotalPrice')
            ->andReturn($this->invoiceData['includingTaxTotalPrice']);
        $invoice->shouldReceive('isPaid')
            ->andReturn(true);
        $invoice->shouldReceive('getPayment')
            ->andReturn($payment);

        return $invoice;
    }

    /**
     * @return Factory
     */
    private function getNewViewFactory()
    {
        $engine = new EngineResolver();
        $engine->register('blade', function () {
            return new CompilerEngine(new BladeCompiler(new Filesystem(), '/tmp'), new Filesystem());
        });

        $finder = new FileViewFinder(new Filesystem(), []);
        $finder->addNamespace('invoices', 'src/views');

        $events = new Dispatcher();

        return new Factory($engine, $finder, $events);
    }

    /**
     * @return HtmlGenerator
     */
    private function getNewHtmlGenerator()
    {
        return new HtmlGenerator($this->getNewInvoice(), $this->getNewViewFactory());
    }

    /**
     * @test
     */
    public function itGenerateHtmlWithAllTheData()
    {
        $htmlGenerator = $this->getNewHtmlGenerator();

        $html = $htmlGenerator->generate();

        $this->assertContains($this->invoiceData['id'], $html);

        $this->assertContains($this->customerData['id'], $html);
        $this->assertContains($this->customerData['name'], $html);
        $this->assertContains($this->customerData['address'], $html);

        $this->assertContains($this->itemsData['ring']['name'], $html);
        $this->assertContains((string) $this->itemsData['ring']['quantity'], $html);
        $this->assertContains((string) $this->itemsData['ring']['excludingTaxUnitPrice'], $html);
        $this->assertContains((string) $this->itemsData['ring']['excludingTaxTotalPrice'], $html);
        $this->assertContains((string) $this->itemsData['ring']['includingTaxTotalPrice'], $html);

        $this->assertContains($this->groupsData['stuff']['name'], $html);

        $this->assertContains($this->groupsData['stuff']['items']['gloves']['name'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['quantity'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['excludingTaxUnitPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['excludingTaxTotalPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['includingTaxTotalPrice'], $html);

        $this->assertContains($this->groupsData['stuff']['items']['armor']['name'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['quantity'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['excludingTaxUnitPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['excludingTaxTotalPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['includingTaxTotalPrice'], $html);

        $this->filesystem->put('/tmp/invoice.html', $html);
    }
}
