<?php namespace QuanticTelecom\Invoices\Tests;

use Illuminate\Filesystem\Filesystem;
use PHPUnit_Framework_TestCase;
use Mockery as m;
use QuanticTelecom\Invoices\Contracts\HtmlGeneratorInterface;
use QuanticTelecom\Invoices\AbstractInvoice as Invoice;
use QuanticTelecom\Invoices\PdfGenerator;

class PdfGeneratorTest extends PHPUnit_Framework_TestCase
{
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

        $invoice = m::mock(Invoice::class);
        $invoice->shouldReceive('getId')->andReturn('2015-03-05-0042');

        return new PdfGenerator($invoice, $this->filesystem, null, $htmlGenerator);
    }

    /**
     * @test
     */
    public function itGenerateAPdf()
    {
        $pdfGenerator = $this->getNewPdfGenerator();

        $pdf = $pdfGenerator->generate();

        $this->filesystem->put('/tmp/invoice.pdf', $pdf);
    }
}
