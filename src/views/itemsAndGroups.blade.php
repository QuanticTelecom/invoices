@foreach($itemsAndGroupsContainer->getItems() as $item)
    <tr>
            <td style="width: {{ 85.2 - $padding }}mm; padding-left: {{ $padding }}mm;">{{ $item->getItemName() }}</td>
            <td style="width: 10mm;">{{ $item->getItemQuantity() }}</td>
            <td style="width: 26mm;">{{ $item->getItemExcludingTaxUnitPrice() }} <small>EUR</small></td>
            <td style="width: 23mm;">{{ $item->getItemExcludingTaxTotalPrice() }} <small>EUR</small></td>
            <td style="width: 12.5mm;">20%</td>
            <td style="width: 23.3mm;">{{ $item->getItemIncludingTaxTotalPrice() }} <small>EUR</small></td>
    </tr>
@endforeach

@foreach($itemsAndGroupsContainer->getGroups() as $group)
    <tr>
        <td style="width: 85.2mm;"  colspan="6">{{ $group->getName() }}</td>
    </tr>

    @include('invoices::itemsAndGroups', ['itemsAndGroupsContainer' => $group, 'padding' => $padding + 10])
@endforeach