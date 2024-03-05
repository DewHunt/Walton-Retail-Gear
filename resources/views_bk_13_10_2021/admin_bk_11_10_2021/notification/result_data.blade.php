@if(isset($notification_list))
    @foreach($notification_list as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->title }}</td>
        <td>{{ $row->message }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="toggle-class push-notification-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <button type="button" data-id="{{ $row->id }}" id="editPushNotificationInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPushNotificationModal">Edit</button> |
            <button type="button" data-id="{{ $row->id }}" id="getPushNotificationInfo" class="btn btn-info btn-sm" data-toggle="modal" data-target="#getPushNotificationModal">Send</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="5" align="center">{!! $notification_list->links() !!}</td></tr>
@endif

                    