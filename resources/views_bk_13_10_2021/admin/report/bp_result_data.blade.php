@if(isset($bpSalesList))
    @foreach($bpSalesList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->bp_name }}</td>
        <td>{{ $row->bp_phone }}</td>
        <td>{{ $row->total_qty }}</td>
        <td>{{ number_format($row->total_sale_amount,2) }}</td>
        <td style="text-align: center;">
            <button type="button" data-id="{{ $row->bp_id }}" id="viewBpOrderDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewOrderDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
    </tr>
    @endforeach
    <tr><td colspan="6" align="center">{!! $bpSalesList->links() !!}</td></tr>
@endif
    


                    