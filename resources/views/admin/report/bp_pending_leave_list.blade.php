@extends('admin.master.master')
@section('content')
<style>
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
</style>
<h4 class="c-grey-900 mB-20">BP Pending Leave</h4>
<div class="row">
    <div class="col-md-9">
        <button id="btnPdf" class="btn btn-primary cur-p btn-xs">TO PDF</button>
        <button class="btn btn-info cur-p btn-xs" onclick="ExportToExcel('xlsx')">TO Excel</button>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="serach" id="serach" class="form-control" />
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table id="dataExport" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="leave_type" style="cursor: pointer;">Leave Type</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="apply_date" style="cursor: pointer;">Apply Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="total_day" style="cursor: pointer;">Total Day</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="start_time" style="cursor: pointer;">Start Time</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="reason" style="cursor: pointer;">Reason</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.report.bp_pending_leave_result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
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
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="disabledSelect">Leave Type<span class="required">*</span></label>
                                <select class="form-control" name="leave_type" id="leaveType">
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Start Date <span class="required">*</span></label>
                                <input type="text" name="start_date" class="form-control start_date" readonly="">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
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
                    </div>

                    <div class="col-md-12">
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