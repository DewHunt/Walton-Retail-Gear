@if(isset($imeiRequirementList))
    @foreach($imeiRequirementList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->imei_number }}</td>
        <td>{{ $row->description }}</td>
        <td>{{ $row->date }}</td>
        {{-- <td>
            <button type="button" data-id="{{ $row->id }}" id="editIMEIinfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editIMEIModal">Edit</button>
        </td> --}}
    </tr>
    @endforeach
	<tr><td colspan="4" align="center">{!! $imeiRequirementList->links() !!}</td></tr>
@endif
    


                    