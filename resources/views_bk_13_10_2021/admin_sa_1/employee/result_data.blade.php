@if(isset($EmployeeList))
    @foreach($EmployeeList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->designation }}</td>
        <td>{{ $row->department }}</td>
        <td>{{ $row->mobile_number }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="employee-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <button type="button" data-id="{{ $row->id }}" id="editEmployeeInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editEmployeeModal">Edit</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="7" align="center">{!! $EmployeeList->links() !!}</td></tr>
@endif
    


                    