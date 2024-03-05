@if(isset($ZoneList))
    @foreach($ZoneList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->zone_name }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="zone-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <button type="button" data-id="{{ $row->id }}" id="editZoneInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editZoneModal">Edit</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="5" align="center">{!! $ZoneList->links() !!}</td></tr>
@endif
    


                    