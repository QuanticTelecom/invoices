<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" style="padding: 0; margin:0">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="padding: 0; margin:auto; width: 210mm; height: 297mm; overflow: hidden; font-family: Courier; font-size: 0.8em; background-image: url('../../public/images/invoice.png')">

<div style="position: absolute; top: 63.5mm; left: 35mm;">{{ $invoice->getId() }}</div>
<div style="position: absolute; top: 67.7mm; left: 48mm;">{{ $invoice->getCreatedAt()->format('d/m/Y') }}</div>
<div style="position: absolute; top: 71.9mm; left: 49.1mm;">{{ $invoice->getDueDate()->format('d/m/Y') }}</div>
<div style="position: absolute; top: 84.5mm; left: 17.2mm;">{{ $customer->getCustomerId() }}</div>

<div style="position: absolute; top: 72mm; left: 110mm; width: 90mm;">
    {{ $customer->getCustomerName() }}<br/>
    {{ $customer->getCustomerAddress() }}
</div>

<table style="width: 180mm; margin: auto; position: absolute; top: 132mm; border-collapse: collapse; border: none;">

    @include('invoices::itemsAndGroups', ['itemsAndGroupsContainer' => $invoice, 'padding' => 0])

</table>
<div style="position: absolute; top: 213.3mm; left: 170mm;">{{ $invoice->getExcludingTaxTotalPrice() }} <small>EUR</small></div>
<div style="position: absolute; top: 217.5mm; left: 170mm;">{{ $invoice->getVatAmount() }} <small>EUR</small></div>
<div style="position: absolute; top: 221.7mm; left: 170mm;">{{ $invoice->getIncludingTaxTotalPrice() }} <small>EUR</small></div>

@if ($invoice->isPaid())
    <div style="position: absolute; top: 249.2mm; left: 69.5mm; font-size: 0.9em;">{{ $invoice->getPayment()->getPaymentDate()->format('d/m/Y') }}</div>
    <div style="position: absolute; top: 249.2mm; left: 96.5mm; font-size: 0.9em;">{{ $invoice->getPayment()->getPaymentName() }}</div>
@endif

</body>
</html>
