<?php namespace QuanticTelecom\Invoices;

use Illuminate\Support\Facades\App;
use Illuminate\View\Factory;
use Illuminate\View\View;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;

class HtmlGenerator implements HtmlGeneratorInterface
{
    /**
     * @var AbstractInvoice
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
     * @param AbstractInvoice $invoice
     * @param Factory $factory
     */
    public function __construct(AbstractInvoice $invoice, Factory $factory = null)
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
