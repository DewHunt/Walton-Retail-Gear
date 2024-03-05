@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Report Dashboard</h4>
<div class="row">
    <div class="masonry-item col-md-3 mY-10">
        <div class="bd bgc-white">
            <div class="layers">
                <div class="layer w-100">
                    <div class="list-group">
                        @include('admin.report.report_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="masonry-item col-md-9 mY-10">
        <div class="bgc-white p-20 bd">
            <div style="border-bottom: 1px solid black;">
                <h4 class="c-grey-900 text-center">BP Attendance Report</h4>
            </div>
            @if(isset($attendanceList))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Selfi Photo</th>
                                <th>BP Name</th>
                                <th>In Status</th>
                                <th>Out Status</th>
                                <th>Location</th>
                                <th>Status & Time</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td>
                                    @if($row->remarks == 1)
                                    <b>{{ 'First In' }}</b><br/>
                                    {{ $row->date }}
                                    @elseif($row->remarks == 2)
                                    <b>{{ 'First Out' }}</b><br/>
                                    {{ $row->date }}
                                    @elseif($row->remarks == 3)
                                    <b>{{ 'Again In' }}</b><br/>
                                    {{ $row->date }}
                                    @elseif($row->remarks == 4)
                                    <b>{{ 'Again Out' }}</b><br/>
                                    {{ $row->date }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection