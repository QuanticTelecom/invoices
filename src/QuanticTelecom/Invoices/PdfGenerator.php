<?php namespace QuanticTelecom\Invoices;

use Illuminate\Filesystem\FileNotFoundException;
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
     * @var Factory
     */
    protected $factory;

    /**
     * @var HtmlGeneratorInterface
     */
    private $htmlGenerator;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @param Filesystem $files
     * @param Factory $factory
     * @param HtmlGeneratorInterface $htmlGenerator
     */
    public function __construct(
        Filesystem $files,
        Factory $factory,
        HtmlGeneratorInterface $htmlGenerator
    ) {
        $this->files = $files;
        $this->factory = $factory;
        $this->htmlGenerator = $htmlGenerator;
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
     * @param InvoiceInterface $invoice
     * @param string $storagePath
     *
     * @return string
     */
    private function writeViewForImaging(InvoiceInterface $invoice, $storagePath)
    {
        $html = $this->htmlGenerator->generate($invoice);
        $storagePath = $storagePath ?: storage_path().'/framework';

        $this->files->put($path = $storagePath.'/'. $invoice->getId() .'.pdf', $html);
        return $path;
    }

    /**
     * Get the rendered PDF of the invoice.
     *
     * @param InvoiceInterface $invoice
     * @param string $storagePath | '/tmp'
     *
     * @return string PDF as a string
     *
     * @throws FileNotFoundException
     */
    public function generate(InvoiceInterface $invoice, $storagePath = '/tmp')
    {
        // To properly capture a screenshot of the invoice view, we will pipe out to
        // PhantomJS, which is a headless browser. We'll then capture a PNG image
        // of the webpage, which will produce a very faithful copy of the page.
        $viewPath = $this->writeViewForImaging($invoice, $storagePath);
        $this->getPhantomProcess($viewPath)
            ->setTimeout(10)->run();

        return $this->files->get($viewPath);
    }
}
