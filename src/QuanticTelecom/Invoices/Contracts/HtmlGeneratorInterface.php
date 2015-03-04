<?php namespace QuanticTelecom\Invoices\Contracts;

use QuanticTelecom\Invoices\AbstractInvoice;

interface HtmlGeneratorInterface
{
    /**
     * Return a new HtmlGenerator instance.
     *
     * @param AbstractInvoice $invoice
     */
    public function __construct(AbstractInvoice $invoice);

    /**
     * Get the rendered HTML content of the invoice.
     *
     * @return string HTML
     */
    public function generate();
}
