<?php namespace QuanticTelecom\Invoices\Contracts;

interface HtmlGeneratorInterface
{
    /**
     * Return a new HtmlGenerator instance.
     *
     * @param InvoiceInterface $invoice
     */
    public function __construct(InvoiceInterface $invoice);

    /**
     * Get the rendered HTML content of the invoice.
     *
     * @return string HTML
     */
    public function generate();
}
