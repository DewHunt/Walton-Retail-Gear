@if(isset($saleList))
@foreach($saleList as $row)
<tr>
	<td>{{ ++$loop->index }}.</td>
	<td>{{ $row->customer_name }}</td>
	<td>{{ $row->customer_phone }}</td>
	<td>{{ $row->sale_date }}</td>
	<td style="text-align: center;">
	   <button type="button" data-id="{{ $row->id }}" id="viewOrderDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewOrderDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
	</td>
</tr>
@endforeach
	<tr><td colspan="5" align="center">{!! $saleList->links() !!}</td></tr>
@endif
    


                    