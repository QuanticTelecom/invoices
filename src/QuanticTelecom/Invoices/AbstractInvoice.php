<?php namespace QuanticTelecom\Invoices;

use Carbon\Carbon;
use QuanticTelecom\Invoices\Contracts\CustomerInterface;
use QuanticTelecom\Invoices\Contracts\IdGeneratorInterface;
use QuanticTelecom\Invoices\Contracts\InvoiceInterface;
use QuanticTelecom\Invoices\Contracts\PaymentInterface;

abstract class AbstractInvoice implements InvoiceInterface
{
    use ItemsContainerTrait;
    use GroupsContainerTrait;

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
     * @var Carbon
     */
    private $createdAt;

    /**
     * @var Carbon
     */
    private $dueDate;

    /**
     * @var PaymentInterface
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
     * Get the ID of the invoice.
     *
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
     * Get the customer.
     *
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Get the price of all items with all taxes.
     *
     * @return float
     */
    abstract public function getIncludingTaxTotalPrice();

    /**
     * Get the price of all items without taxes.
     *
     * @return float
     */
    abstract public function getExcludingTaxTotalPrice();

    /**
     * Get the amount of VAT of the invoice.
     *
     * @return float
     */
    public function getVatAmount()
    {
        return $this->getIncludingTaxTotalPrice() - $this->getExcludingTaxTotalPrice();
    }

    /**
     * Get the due date of the invoice.
     *
     * @return Carbon
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set the due date of the invoice, if null, set the current date.
     *
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
     * Get the creation date of the invoice.
     *
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date of the invoice, if null, set the current date.
     *
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
     * Get the payment instance of the invoice if paid or null.
     *
     * @return PaymentInterface | null
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set the payment instance for a paid invoice.
     *
     * @param PaymentInterface $payment
     * @return self
     */
    public function setPayment(PaymentInterface $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * Check if the payment instance is set.
     *
     * @return bool
     */
    public function isPaid()
    {
        return !is_null($this->payment);
    }
}
