@extends('admin.master.master')
@section('content')
<style>
    .cp {
        padding:5px;
    }
    .csearch {
        width:285px;
    }           
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .cp {
            padding:5px;
        }
        .csearch {
            width:300px;
        }
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .cp {
            padding:5px;
        }
        .csearch {
            width:300px;
        }
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .cp {
            padding:5px;
        }
        .csearch {
            width:300px;
        }
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
</style>
<h4 class="c-grey-900 mB-20">IMEI Dispute List</h4>
<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="imei_number" style="cursor: pointer;">IMEI</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="description" style="cursor: pointer;">Description</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="comments" style="cursor: pointer;">Comments</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="date" style="cursor: pointer;">Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th style="width:5px">Action </th>
            </tr>
        </thead>
        <tbody>
            @include('admin.imei_dispute.result_data')
        </tbody>
    </table>
</div>

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editIMEIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update IMEI Dispute</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateIMEIDispute">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Status<span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select" style="width: 100%;" name="status" required="">
                                    <option value="">Select</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Reported</option>
                                    <option value="2">Decline</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Comments</label>
                                <textarea class="form-control description" name="comments"></textarea>
                                <input type="hidden" name="imei_number" class="imeiNumber">
                                <input type="hidden" name="imei_id" class="imeidisputeId">
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

