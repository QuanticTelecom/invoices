# Quantic Telecom Invoices

[![Build Status](https://travis-ci.org/QuanticTelecom/invoices.svg?branch=develop)](https://travis-ci.org/QuanticTelecom/invoices)

This package gives you an easy way to manage invoices.

## Installation

Per usual, install Invoices through Composer.

```js
"require": {
    "quantic-telecom/invoices": "~1.0"
}
```

## Contracts

### Invoices

An invoice is a object inheriting from `AbstractInvoice`. It is composed of an ID, a customer (represented by `CustomerInterface`) and two dates: the due date and the creation date (these two are represented by Carbon instances).

It provides abstract methods to compute the total price (with and without taxes). The french VAT rate is currently hard code into the `AbstractInvoice` class (`$vatRate` static property) but will be extract in a future release. Implementations of `AbstractInvoice` don't have to use this value. The VAT amount is compute base on the subtraction between the implementation of the `getIncludingTaxTotalPrice` and the `getExcludingTaxTotalPrice` abstract methods.

A payment (represented by `PaymentInterface`) can be set in order to have a paid invoice (with the method `isPaid`).

### Containers

An invoice is also a container of items and a container of groups of items.

An item (represented by `ItemInterface`) is a simple line of the invoice (name, quantity, unit prices and total prices with and without taxes).

A group of items container (represented by `GroupOfItemsInterface`) has a name. As an invoice, it's a container of items and a container of groups of items recursively.

### Invoice generation

An invoice could be generated into HTML thanks to `HtmlGeneratorInterface` or in PDF thanks to `PdfGeneratorInterface` (note that our implementation of  `PdfGeneratorInterface` requires an `HtmlGeneratorInterface` implementation).

## Implementations

### Invoices

This package provides two implementations of `AbstractInvoice`:
- `ExcludingTaxInvoice` which sum all the excluding tax prices of the items and group of items and then compute the including tax price based on that value.
- `IncludingTaxInvoice` which sum all the including tax prices of the items and group of items and then compute the excluding tax price based on that value.

### Containers

This packages also provides basic implementations for `ItemInterface` (`Item`) and `GroupOfItemsInterface` (`GroupOfItems`)

### Generators

The implementation of `HtmlGeneratorInterface` is named `HtmlGenerator` and is based on Laravel Views.

An implementation of `PdfGeneratorInterface` named `PdfGenerator` is provided based on PhantomJS (a javascript browser). Our implementation of `PdfGeneratorInterface` requires an implementation of `HtmlGeneratorInterface` to process.
