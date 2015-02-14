<?php namespace QuanticTelecom\Invoices\Contracts;

interface IdGeneratorInterface {

    /**
     * Return a new ID to use for an invoice
     *
     * @return string
     */
    public function generateNewId();
}