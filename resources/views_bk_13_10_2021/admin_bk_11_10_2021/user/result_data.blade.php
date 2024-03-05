@if(isset($GetUser))
    @foreach($GetUser as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->name }}</td>
        <td>
            @if($row->employee_id > 0)
            {{ 'Emp ID# '.$row->employee_id }}
            @elseif($row->bp_id > 0)
            {{ 'BP ID# '.$row->bp_id }}
            @elseif($row->retailer_id > 0)
            {{ 'Retailer ID# '.$row->retailer_id }}
            @endif
        </td>
        <td>{{ $row->email }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="user-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <a href="{{ url('user.menu_permission_list'.'/'.$row->id) }}" class="btn btn-info btn-sm">Menu Permission</a>

            <button type="button" data-id="{{ $row->id }}" id="editUserInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal">Edit</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="6" align="center">{!! $GetUser->links() !!}</td></tr>
@endif
    


                    