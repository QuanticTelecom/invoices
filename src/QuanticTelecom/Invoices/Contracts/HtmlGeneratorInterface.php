<?php namespace QuanticTelecom\Invoices\Contracts;

interface HtmlGeneratorInterface
{
    /**
     * Get the rendered HTML content of the invoice.
     *
     * @param InvoiceInterface $invoice
     *
     * @return string HTML
     */
    public function generate(InvoiceInterface $invoice);
}
