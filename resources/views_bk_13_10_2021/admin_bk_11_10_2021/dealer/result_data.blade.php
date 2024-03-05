@if(isset($DelarList))
	@foreach($DelarList as $row)
	<tr>
		<td>{{ ++$loop->index }}.</td>
		<td>{{ $row->dealer_code }}</td>
		<td>{{ $row->alternate_code }}</td>
		<td>{{ $row->dealer_name }}</td>
		<td>
			{{ $row->dealer_address }},<br/>
			{{ 'City: '.$row->city }}<br/>
			{{ 'Division: '.$row->division }}
		</td>
		<td>{{ $row->dealer_phone_number }}</td>
		<td>{{ $row->rsm }}</td>
		<td>
			<input data-id="{{ $row->id }}" class="dealer-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
		</td>
		<td>
			<button type="button" data-id="{{ $row->id }}" id="editDealerInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editDelarModal">Edit</button>
		</td>
	</tr>
	@endforeach
	
	<tr><td colspan="9" align="center">{!! $DelarList->links() !!}</td></tr>
@endif




                    