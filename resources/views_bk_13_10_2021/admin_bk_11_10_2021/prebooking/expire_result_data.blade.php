@if(isset($prebooking_list))
    @foreach($prebooking_list as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->model }}</td>
        <td>{{ $row->color }}</td>
        <td>{{ date('d-m-Y',strtotime($row->start_date)) }}</td>
        <td>{{ date('d-m-Y',strtotime($row->end_date)) }}</td>
        <td>{{number_format($row->minimum_advance_amount,2)}}</td>
        <td>{{ $row->max_qty }}</td>
        <td>{{number_format($row->price,2)}}</td>
        <td>
            <input data-id="{{ $row->id }}" class="toggle-class pre-booking-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <button type="button" data-id="{{ $row->id }}" id="editPreBookingInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPreBookingModal">Edit</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="10" align="center">{!! $prebooking_list->links() !!}</td></tr>
@endif

                    