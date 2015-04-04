<?php namespace QuanticTelecom\Invoices;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\InvoiceInterface;
use QuanticTelecom\Invoices\Contracts\PdfGeneratorInterface;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Class PdfGenerator
 * @package QuanticTelecom\Invoices
 */
class PdfGenerator implements PdfGeneratorInterface
{
    /**
     * @var InvoiceInterface
     */
    private $invoice;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * Return a new PdfGenerator instance.
     *
     * @param InvoiceInterface $invoice
     * @param Filesystem $files
     * @param Factory $factory
     * @param HtmlGeneratorInterface $htmlGenerator
     */
    public function __construct(
        InvoiceInterface $invoice,
        Filesystem $files = null,
        Factory $factory = null,
        HtmlGeneratorInterface $htmlGenerator = null
    ) {
        $this->invoice = $invoice;

        if (is_null($files)) {
            $this->files = new Filesystem();
        } else {
            $this->files = $files;
        }

        if (is_null($htmlGenerator)) {
            $this->htmlGenerator = new HtmlGenerator($invoice, $factory);
        } else {
            $this->htmlGenerator = $htmlGenerator;
        }
    }

    /**
     * Get the PhantomJS process instance.
     *
     * @param string $viewPath
     * @return \Symfony\Component\Process\Process
     */
    private function getPhantomProcess($viewPath)
    {
        $system = $this->getSystem();
        $phantom = __DIR__.'/bin/'.$system.'/phantomjs'.$this->getExtension($system);
        return new Process($phantom.' invoice.js '.$viewPath, __DIR__);
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
     * Write the view HTML so PhantomJS can access it.
     *
     * @param string $storagePath
     * @return string
     */
    private function writeViewForImaging($storagePath)
    {
        $html = $this->htmlGenerator->generate();
        $storagePath = $storagePath ?: storage_path().'/framework';

        $this->files->put($path = $storagePath.'/'. $this->invoice->getId() .'.pdf', $html);
        return $path;
    }

    /**
     * Get the rendered PDF of the invoice.
     *
     * @param string $storagePath | '/tmp'
     * @return string PDF as a string
     */
    public function generate($storagePath = '/tmp')
    {
        // To properly capture a screenshot of the invoice view, we will pipe out to
        // PhantomJS, which is a headless browser. We'll then capture a PNG image
        // of the webpage, which will produce a very faithful copy of the page.
        $viewPath = $this->writeViewForImaging($storagePath);
        $this->getPhantomProcess($viewPath)
            ->setTimeout(10)->run();

        return $this->files->get($viewPath);
    }
}
