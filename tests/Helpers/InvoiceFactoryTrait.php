<?php namespace QuanticTelecom\Invoices\Tests\Helpers;

use Mockery as m;
use Carbon\Carbon;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
use QuanticTelecom\Invoices\Contracts\PaymentInterface;
use QuanticTelecom\Invoices\GroupOfItems;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\AbstractInvoice as Invoice;

trait InvoiceFactoryTrait
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
}
