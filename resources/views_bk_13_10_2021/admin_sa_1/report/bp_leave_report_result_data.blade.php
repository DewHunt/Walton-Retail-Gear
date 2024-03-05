@foreach($leaveList as $row)
<tr>
    <td>{{ ++$loop->index }}.</td>
    <td>{{ $row->bp_name }}</td>
    <td>{{ $row->leave_type }}</td>
    <td>{{ date('d-m-Y', strtotime($row->apply_date)) }}</td>
    <td>{{ date('d-m-Y', strtotime($row->start_date)) }}</td>
    <td>{{ $row->total_day }}</td>
    <td>{{ $row->start_time }}</td>
    <td>{{ $row->reason }}</td>
    <td>
        @if($row->status == 'Pending')
        <span class="btn cur-p btn-warning" style="padding: 0px 5px;">{{ 'Pending' }}</span>
        @elseif($row->status == 'Approved')
        <span class="btn cur-p btn-success" style="padding: 0px 5px;">{{ 'Approved' }}</span>
        @else
        <span class="btn cur-p btn-danger" style="padding: 0px 5px;">{{ 'Cancel' }}</span>
        @endif
    </td>
    <td>
        <button type="button" data-id="{{ $row->id }}" id="leaveEdit" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#leaveEditModal" style="padding: 2px 6px;"><i class="fa fa-pencil" aria-hidden="true"></i></button>

        {{-- 
        <button type="button" data-id="{{ $row->id }}" class="btn cur-p btn-success btn-xs" style="padding: 2px 6px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button> 
        --}}

        {{-- 
        <button type="button" data-id="{{ $row->id }}" id="viewLeaveDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewLeaveDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button> 
        --}}
    </td>
</tr>
@endforeach
<tr><td colspan="10" align="center">{!! $leaveList->links() !!}</td></tr>
    


                    