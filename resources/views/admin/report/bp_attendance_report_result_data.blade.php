@if(isset($attendanceList))
@foreach($attendanceList as $row)
<tr>
    <td>{{ ++$loop->index }}.</td>
    <td>
        @if(isset($row->selfi_pic) && !empty($row->selfi_pic))
        <img src="{{ asset('public/upload/bpattendance/'.$row->selfi_pic) }}" alt="" width="50" height="50"/></td>
        @else
        <img src="{{ asset('public/upload/no-image.png') }}" alt="" width="50" height="50"/></td>
        @endif
    </td>
    <td>{{ $row->bp_name }}</td>
    <td>{{ $row->in_status }}</td>
    <td>{{ $row->out_status }}</td>
    <td>{{ $row->location }}</td>
    <td>{{ $row->date_time }}</td>
    <td>
        {{ $row->in_time }}<br/>
        {{ $row->in_time_location }}
    </td>
    <td>
        {{ $row->out_time }}<br/>
        {{ $row->out_time_location }}
    </td>
    <td style="text-align: center;">
        {{-- 
        <a target="_blank" class="btn cur-p  btn-info btn-xs" href="{{ url('bpAttendanceDetails') }}/{{ $row->id }}/{{ $row->date_time }}" style="padding: 2px 6px;">
          <i class="fa fa-eye" aria-hidden="true"></i>
        </a> 
        --}}

        <a href="javascript:void(0)">
        <button type="button" data-id="{{ $row->id }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewAttendanceDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </a>
    </td>
</tr>
@endforeach
<tr><td colspan="10" align="center">{!! $attendanceList->links() !!}</td></tr>
@endif
    


                    