<?php namespace QuanticTelecom\Invoices\Contracts;

interface PdfGeneratorInterface
{
    /**
     * Return a new PdfGenerator instance.
     *
     * @param InvoiceInterface $invoice
     */
    public function __construct(InvoiceInterface $invoice);

    /**
     * Get the rendered PDF of the invoice.
     *
     * @return string PDF as a string
     */
    public function generate();
}
