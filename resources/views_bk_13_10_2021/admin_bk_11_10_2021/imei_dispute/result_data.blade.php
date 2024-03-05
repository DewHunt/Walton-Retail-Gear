@if(isset($imeiDisputeList))
    @foreach($imeiDisputeList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->imei_number }}</td>
        <td>{{ $row->description }}</td>
        <td>{{ $row->comments }}</td>
        <td>{{ $row->date }}</td>
        <td>
            @if($row->status == 0) 
            <button type="button" class="btn cur-p btn-info btn-color btn-sm">{{ 'Pending' }}</button>
            @elseif($row->status == 1)
            <button type="button" class="btn cur-p btn-info btn-color btn-sm">{{ 'Reported' }}</button>
            @elseif($row->status == 2)
            <button type="button" class="btn cur-p btn-info btn-color btn-sm">{{ 'Decline' }}</button>
            @endif
        </td>
        <td>
            @if($row->status == 0)
            <button type="button" data-id="{{ $row->id }}" id="editIMEIinfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editIMEIModal">Action</button>
            @elseif($row->status == 1 )
            <button type="button" data-id="{{ $row->id }}" class="btn btn-warning btn-sm">Reported</button>
            @elseif($row->status == 2 )
            <button type="button" data-id="{{ $row->id }}" class="btn btn-danger btn-sm">Decline</button>
            @endif
        </td>
    </tr>
    @endforeach
	<tr><td colspan="7" align="center">{!! $imeiDisputeList->links() !!}</td></tr>
@endif
    


                    