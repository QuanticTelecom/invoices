<?php namespace QuanticTelecom\Invoices;

use CanGelis\PDF\PDF;
use Illuminate\View\Factory;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\PdfGeneratorInterface;
use RuntimeException;

class PdfGenerator implements PdfGeneratorInterface
{
    /**
     * @var AbstractInvoice
     */
    private $invoice;

    /**
     * Return a new PdfGenerator instance.
     *
     * @param AbstractInvoice $invoice
     * @param Factory $factory
     * @param HtmlGeneratorInterface $htmlGenerator
     */
    public function __construct(
        AbstractInvoice $invoice,
        Factory $factory = null,
        HtmlGeneratorInterface $htmlGenerator = null
    ) {
        $this->invoice = $invoice;

        if (is_null($htmlGenerator)) {
            $this->htmlGenerator = new HtmlGenerator($invoice, $factory);
        } else {
            $this->htmlGenerator = $htmlGenerator;
        }

        $this->pdf = new PDF($this->getWkhtmltopdfPath());
    }

    /**
     * Get the WkHTMLtoPDF path.
     *
     * @return string
     */
    private function getWkhtmltopdfPath()
    {
        $system = $this->getSystem();

        return __DIR__.'/bin/'.$system.'/wkhtmltopdf'.$this->getExtension($system);
    }

    /**
     * Get the operating system for the current platform.
     *
     * @return string
     */
    private function getSystem()
    {
        $uname = strtolower(php_uname());
        if (str_contains($uname, 'darwin')) {
            return 'macosx';
        } elseif (str_contains($uname, 'win')) {
            return 'windows';
        } elseif (str_contains($uname, 'linux')) {
            return PHP_INT_SIZE === 4 ? 'linux-i686' : 'linux-x86_64';
        } else {
            throw new RuntimeException("Unknown operating system.");
        }
    }

    /**
     * Get the binary extension for the system.
     *
     * @param string $system
     * @return string
     */
    private function getExtension($system)
    {
        return $system == 'windows' ? '.exe' : '';
    }

    /**
     * Get the rendered PDF of the invoice.
     *
     * @return string PDF as a string
     */
    public function generate()
    {
        $html = $this->htmlGenerator->generate();

        return $this->pdf->loadHTML($html)->pageSize('A4')->get();
    }
}
