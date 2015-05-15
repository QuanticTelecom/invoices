<?php

namespace QuanticTelecom\Invoices\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use PHPUnit_Framework_TestCase;
use Mockery as m;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;
use QuanticTelecom\Invoices\AbstractInvoice as Invoice;
use QuanticTelecom\Invoices\Contracts\InvoiceInterface;
use QuanticTelecom\Invoices\PdfGenerator;

class PdfGeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InvoiceInterface
     */
    protected $invoice;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = new Filesystem();
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @return PdfGenerator
     */
    private function getNewPdfGenerator()
    {
        $html = $this->filesystem->get('tests/stubs/invoice.html');

        $htmlGenerator = m::mock(HtmlGeneratorInterface::class);
        $htmlGenerator->shouldReceive('generate')->andReturn($html);

        $this->invoice = m::mock(Invoice::class);
        $this->invoice->shouldReceive('getId')->andReturn('2015-03-05-0042');

        return new PdfGenerator($this->filesystem, $this->getNewViewFactory(), $htmlGenerator);
    }

    /**
     * @test
     */
    public function itGenerateAPdf()
    {
        $pdfGenerator = $this->getNewPdfGenerator();

        $pdf = $pdfGenerator->generate($this->invoice);

        $this->filesystem->put('/tmp/invoice.pdf', $pdf);
    }

    protected function getNewViewFactory()
    {
        return new Factory(new EngineResolver(), new FileViewFinder($this->filesystem, []), new Dispatcher());
    }
}
