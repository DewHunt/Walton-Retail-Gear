@if(isset($preBookingOrderList) && !$preBookingOrderList->isEmpty())
    @foreach($preBookingOrderList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->model }}</td>
        <td>{{ $row->color }}</td>
        <td>{{ $row->bookingQty }}</td>
        <td style="text-align: center;">
        <button type="button" data-id="{{ $row->model }}" id="viewOrderSalesDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewOrderSalesDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
    </tr>
    @endforeach
    <tr><td colspan="5" align="center">{!! $preBookingOrderList->links() !!}</td></tr>
@endif

    


                    