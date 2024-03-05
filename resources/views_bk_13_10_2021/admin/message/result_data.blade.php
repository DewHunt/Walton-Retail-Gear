@if(isset($MessageList))
    @foreach($MessageList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->message }}<br/>{{ $row->reply_user_name }} - {{ $row->phone }} - {{ $row->zone }}</td>
        <td>{{ $row->date_time }}</td>
        <td style="text-align: center;">
            <button type="button" data-id="{{ $row->reply_for }}" id="MessageDetailsView" class="btn cur-p btn-info btn-xs cvbtn" data-toggle="modal" data-target="#viewMessageDetailsModal" style="padding: 2px 6px;">
                 <i class="fa fa-eye" aria-hidden="true"></i>
            </button>
            <input type="hidden" id="MsgId" value="{{ $row->id }}"/>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="4" align="center">{!! $MessageList->links() !!}</td></tr>
@endif
    


                    