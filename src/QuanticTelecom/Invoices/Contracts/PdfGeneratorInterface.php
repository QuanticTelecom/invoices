<?php namespace QuanticTelecom\Invoices\Contracts;

use QuanticTelecom\Invoices\AbstractInvoice;

interface PdfGeneratorInterface
{
    /**
     * Return a new PdfGenerator instance.
     *
     * @param AbstractInvoice $invoice
     */
    public function __construct(AbstractInvoice $invoice);

    /**
     * Get the rendered PDF of the invoice.
     *
     * @return string PDF as a string
     */
    public function generate();
}
