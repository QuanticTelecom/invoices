<?php namespace QuanticTelecom\Invoices;

class GroupOfItems
{
    use ItemsContainerTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @param $name string Name of the group.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
