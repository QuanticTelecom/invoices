@foreach($itemsAndGroupsContainer->getItems() as $item)

    <tr style="font-family: Courier;">
        <td class="big"><span style="padding-left: {{ $padding }}px;">{{ $item->getItemName() }}</span></td>
        <td class="small">{{ $item->getItemQuantity() }}</td>
        <td class="medium">{{ $item->getItemExcludingTaxUnitPrice() }} <small>EUR</small></td>
        <td class="medium">{{ $item->getItemExcludingTaxTotalPrice() }} <small>EUR</small></td>
        <td class="small">20%</td>
        <td class="large">{{ $item->getItemIncludingTaxTotalPrice() }} <small>EUR</small></td>
    </tr>

@endforeach

@foreach($itemsAndGroupsContainer->getGroups() as $group)
    <tr style="font-family: Courier;">
        <td colspan="6"><span style="padding-left: {{ $padding }}px;">{{ $group->getName() }}</span></td>
    </tr>

    @include('invoices::itemsAndGroups', ['itemsAndGroupsContainer' => $group, 'padding' => $padding + 30])
@endforeach