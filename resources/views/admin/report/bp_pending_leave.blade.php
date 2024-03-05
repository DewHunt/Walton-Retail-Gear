@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">BP Pending Leave</h4>
<div class="row">
    <div class="masonry-item col-md-12 mY-10">
        <div class="bgc-white p-20 bd new-bp-css">
            @if(isset($leaveList))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>BP Name</th>
                                <th>Leave Type</th>
                                <th>Apply Date</th>
                                <th>Start Date</th>
                                <th>Total Day</th>
                                <th>Start Time</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveList as $row)
                            <tr>
                                <td>{{ ++$loop->index }}.</td>
                                <td>{{ $row->bp_name }}</td>
                                <td>{{ $row->leave_type }}</td>
                                <td>{{ date('d-m-Y', strtotime($row->apply_date)) }}</td>
                                <td>{{ $row->start_date }}</td>
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
                                    <button type="button" data-id="{{ $row->id }}" id="leaveEdit" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#leaveEditModal" style="padding: 0px 5px;"><i class="fa fa-pencil" aria-hidden="true"></i></button>

                                    {{-- 
                                    <button type="button" data-id="{{ $row->id }}" class="btn cur-p btn-success btn-xs" style="padding: 2px 6px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button> 
                                    --}}
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

<!--Edit & Update Modal Start -->
<div class="modal fade" id="leaveEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Leave</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="leaveUpdate">
            <input type="hidden" name="update_id" id="update_id"/>
            <input type="hidden" name="_method" value="PUT"/>
            @csrf
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="disabledSelect">Leave Type <span class="required">*</span></label>
                        <select class="form-control" name="leave_type" id="leaveType">
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Start Date <span class="required">*</span></label>
                        <input type="text" name="start_date" class="form-control start_date" readonly="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>Total Day <span class="required">*</span></label>
                        <input type="text" name="total_day" class="form-control total_day">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Start Time</label>
                        <input type="text" name="start_time" class="form-control start_time">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="disabledSelect">Reason<span class="required">*</span></label>
                        <select class="form-control" name="reason" id="leaveReason">
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="disabledSelect">Status<span class="required">*</span></label>
                        <select class="form-control" name="status" id="leaveStatus">
                            <option value="Approved" id="option1">Approved</option>
                            <option value="Pending" id="option2">Pending</option> 
                            <option value="Cancel" id="option3">Cancel</option> 
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection