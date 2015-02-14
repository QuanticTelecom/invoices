<?php namespace QuanticTelecom\Invoices;

use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\Contracts\IdGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;

abstract class Invoice {

    /**
     * VAT rate in France
     *
     * @var float
     */
    public static $vatRate = 0.2;

    /**
     * @var string
     */
    private $id;

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var ItemInterface[]
     */
    public $items = [];

    /**
     * @param IdGeneratorInterface $idGenerator
     * @param CustomerInterface $customer
     */
    public function __construct(IdGeneratorInterface $idGenerator, CustomerInterface $customer)
    {
        $this->setId($idGenerator->generateNewId());

        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    protected function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Add an item to the invoice
     *
     * @param ItemInterface $item
     * @return $this
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Add an item to the invoice
     *
     * @return ItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return float
     */
    abstract public function getIncludingTaxTotalPrice();

    /**
     * @return float
     */
    abstract public function getExcludingTaxTotalPrice();

    /**
     * @return float
     */
    public function getVatAmount()
    {
        return $this->getIncludingTaxTotalPrice() - $this->getExcludingTaxTotalPrice();
    }
}