@extends('admin.master.master')
@section('content')
<style>
    .mbtnSearch {
        margin-top: 30px;
    }			
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        body {
            font-size: 23px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
        }
        .table-bordered th {
            font-size: 23px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .mbtnSearch {
            margin-top: 53px;
        }
        .bp-col-xs {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333% !important;
            flex: 0 0 33.33333% !important;
            max-width: 33.33333% !important;
        }
        .bp-col-sm {
            -ms-flex: 0 0 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .btn-float {
            float: right;
        }
    }

    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
        }
        .table-bordered th {
            font-size: 20px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .mbtnSearch {
            margin-top: 53px;
        }
        .bp-col-xs {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333% !important;
            flex: 0 0 33.33333% !important;
            max-width: 33.33333% !important;
        }
        .bp-col-sm {
            -ms-flex: 0 0 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .btn-float {
            float: right;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
        }
        .table-bordered th {
            font-size: 20px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .mbtnSearch {
            margin-top: 53px;
        }
        .bp-col-xs {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333% !important;
            flex: 0 0 33.33333% !important;
            max-width: 33.33333% !important;
        }
        .bp-col-sm {
            -ms-flex: 0 0 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .btn-float {
            float: right;
        }
    }
</style>
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
        <div class="bgc-white p-20 bd new-bp-css">
            <form method="post" action="{{ url('bp_attendance_report') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 bp-col-sm" style="padding: 0px 5px;">
                        <label>BP</label>
                        <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-3 bp-col-sm">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepicker" name="start_date">
                    </div>

                    <div class="form-group col-md-3 bp-col-sm">
                        <label>End Date</label>
                        <input type="text" class="form-control datepicker" name="end_date">
                    </div>

                    <div class="col-md-2 mb-3 bp-col-sm">
                        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
                    </div>
                </div>
            </form>


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
                            <th>Selfi Photo</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="in_status" style="cursor: pointer;">In Status</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Out Status</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Location</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Attendance Date</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">In Time & Location</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Out Time & Location</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.report.bp_attendance_report_result_data')
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
            </div>
        </div>
    </div>
</div>


<!--View Product Modal Start -->
<div class="modal fade" id="viewAttendanceDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="salesInfo">

                        </table>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="attendanceList">
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

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div>
<!--View Product Modal End -->
@endsection