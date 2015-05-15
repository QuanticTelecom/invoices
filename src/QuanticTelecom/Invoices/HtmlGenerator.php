<?php

namespace QuanticTelecom\Invoices;

use Illuminate\View\Factory;
use Illuminate\View\View;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\InvoiceInterface;

/**
 * Class HtmlGenerator.
 */
class HtmlGenerator implements HtmlGeneratorInterface
{
    /**
     * View factory instance.
     *
     * @var Factory
     */
    private $factory;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get the rendered HTML content of the invoice.
     *
     * @param InvoiceInterface $invoice
     *
     * @return string HTML
     */
    public function generate(InvoiceInterface $invoice)
    {
        return $this->view($invoice)->render();
    }

    /**
     * Get the View instance for the invoice.
     *
     * @param InvoiceInterface $invoice
     *
     * @return View
     */
    private function view(InvoiceInterface $invoice)
    {
        $data = [
            'invoice' => $invoice,
            'customer' => $invoice->getCustomer(),
        ];

        return $this->factory->make('invoices::invoice', $data);
    }
}
