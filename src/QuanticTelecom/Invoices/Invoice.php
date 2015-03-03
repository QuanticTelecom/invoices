<?php namespace QuanticTelecom\Invoices;

use Carbon\Carbon;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\Contracts\IdGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\ItemInterface;
use QuanticTelecom\Invoices\Contracts\Payment;

abstract class Invoice
{
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
     * @var Carbon
     */
    private $createdAt;

    /**
     * @var Carbon
     */
    private $dueDate;

    /**
     * @var Payment
     */
    private $payment;

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

    /**
     * @return Carbon
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param Carbon $dueDate | null
     * @return self
     */
    public function setDueDate(Carbon $dueDate = null)
    {
        if (is_null($dueDate)) {
            $dueDate = Carbon::now();
        }

        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $createdAt | null
     * @return self
     */
    public function setCreatedAt(Carbon $createdAt = null)
    {
        if (is_null($createdAt)) {
            $createdAt = Carbon::now();
        }

        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     * @return self
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    public function isPaid()
    {
        return !is_null($this->payment);
    }
}
