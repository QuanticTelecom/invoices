<?php

namespace QuanticTelecom\Invoices\Contracts;

interface PdfGeneratorInterface
{
    /**
     * Get the rendered PDF of the invoice.
     *
     * @param InvoiceInterface $invoice
     *
     * @return string PDF as a string
     */
    public function generate(InvoiceInterface $invoice);
}
