<?php namespace QuanticTelecom\Invoices;

use Illuminate\View\Factory;
use Illuminate\View\View;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\InvoiceInterface;

class HtmlGenerator implements HtmlGeneratorInterface
{
    /**
     * @var InvoiceInterface
     */
    private $invoice;

    /**
     * View factory instance.
     *
     * @var Factory
     */
    private $factory;

    /**
     * Return a new HtmlGenerator instance.
     *
     * @param InvoiceInterface $invoice
     * @param Factory $factory
     */
    public function __construct(InvoiceInterface $invoice, Factory $factory = null)
    {
        $this->invoice = $invoice;

        if (!is_null($factory)) {
            $this->factory = $factory;
        }
    }

    /**
     * Get the rendered HTML content of the invoice.
     *
     * @return string
     */
    public function generate()
    {
        return $this->view()->render();
    }

    /**
     * Get the View instance for the invoice.
     *
     * @return View
     */
    private function view()
    {
        $data = [
            'invoice' => $this->invoice,
            'customer' => $this->invoice->getCustomer(),
        ];

        return $this->factory->make('invoices::invoice', $data);
    }
}
