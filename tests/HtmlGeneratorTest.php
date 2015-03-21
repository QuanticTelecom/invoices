<?php namespace QuanticTelecom\Invoices\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use PHPUnit_Framework_TestCase;
use Mockery as m;
use QuanticTelecom\Invoices\HtmlGenerator;
use QuanticTelecom\Invoices\Tests\Helpers\InvoiceStubFactoryTrait;

class HtmlGeneratorTest extends PHPUnit_Framework_TestCase
{
    use InvoiceStubFactoryTrait;

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
     * @return Factory
     */
    private function getNewViewFactory()
    {
        $engine = new EngineResolver();
        $engine->register('blade', function () {
            return new CompilerEngine(new BladeCompiler(new Filesystem(), '/tmp'), new Filesystem());
        });

        $finder = new FileViewFinder(new Filesystem(), []);
        $finder->addNamespace('invoices', 'src/views');

        $events = new Dispatcher();

        return new Factory($engine, $finder, $events);
    }

    /**
     * @return HtmlGenerator
     */
    private function getNewHtmlGenerator()
    {
        return new HtmlGenerator($this->getNewInvoice(), $this->getNewViewFactory());
    }

    /**
     * @test
     */
    public function itGenerateHtmlWithAllTheData()
    {
        $htmlGenerator = $this->getNewHtmlGenerator();

        $html = $htmlGenerator->generate();

        $this->assertContains($this->invoiceData['id'], $html);

        $this->assertContains($this->customerData['id'], $html);
        $this->assertContains($this->customerData['name'], $html);
        $this->assertContains($this->customerData['address'], $html);

        $this->assertContains($this->itemsData['ring']['name'], $html);
        $this->assertContains((string) $this->itemsData['ring']['quantity'], $html);
        $this->assertContains((string) $this->itemsData['ring']['excludingTaxUnitPrice'], $html);
        $this->assertContains((string) $this->itemsData['ring']['excludingTaxTotalPrice'], $html);
        $this->assertContains((string) $this->itemsData['ring']['includingTaxTotalPrice'], $html);

        $this->assertContains($this->groupsData['stuff']['name'], $html);

        $this->assertContains($this->groupsData['stuff']['items']['gloves']['name'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['quantity'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['excludingTaxUnitPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['excludingTaxTotalPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['gloves']['includingTaxTotalPrice'], $html);

        $this->assertContains($this->groupsData['stuff']['items']['armor']['name'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['quantity'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['excludingTaxUnitPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['excludingTaxTotalPrice'], $html);
        $this->assertContains((string) $this->groupsData['stuff']['items']['armor']['includingTaxTotalPrice'], $html);

        $this->filesystem->put('/tmp/invoice.html', $html);
    }
}
