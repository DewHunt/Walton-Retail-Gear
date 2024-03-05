@foreach($saleList as $row)
<tr>
<td>{{ ++$loop->index }}.</td>
<td>{{ $row->customer_name }}</td>
<td>{{ $row->customer_phone }}</td>
<td>{{ $row->sale_date }}</td>
<td>
    @if($row->order_type == 1)
    <span class="btn cur-p btn-info" style="padding: 0px 5px;">{{ 'Online' }}</span>
    @elseif($row->order_type == 2)
    <span class="btn cur-p btn-success" style="padding: 0px 5px;">{{ 'Offline' }}</span>
    @endif
</td>
<td>
    @if($row->status == 1)
    <span class="btn cur-p btn-warning" style="padding: 0px 5px;">{{ 'Pending' }}</span>
     @elseif($row->status == 2)
    <span class="btn cur-p btn-danger" style="padding: 0px 5px;">{{ 'Decline' }}</span>
    @elseif($row->status == 0)
    <span class="btn cur-p btn-success" style="padding: 0px 5px;">{{ 'Success' }}</span>
    @endif
</td>
<td style="text-align: center;">
   <button type="button" data-id="{{ $row->id }}" id="viewOrderDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewOrderDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
</td>
<td>
@if($row->status == 1)
<button type="button" data-id="{{ $row->id }}" id="updateOrderStatus" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateOrderStatusModal">Action</button>
@elseif($row->status == 0 )
<button type="button" class="btn btn-success btn-sm">Success</button>
@endif
</td>
</tr>
@endforeach
<tr><td colspan="8" align="center">{!! $saleList->links() !!}</td></tr>
    


                    