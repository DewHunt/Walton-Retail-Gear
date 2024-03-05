@extends('admin.master.master')
@section('content')
<style type="text/css">
.table-bordered th {
    border: 1px solid #e9ecef;
    font-size: 12px !important;
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
        .bgc-white .btn {
            padding: 0.7rem 0.75rem !important;
            font-size: 1.3rem !important;
        }
        .table-bordered th {
            font-size: 23px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
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
        .bgc-white .btn {
            padding: 0.7rem 0.75rem !important;
            font-size: 1.3rem !important;
        }
        .table-bordered th {
            font-size: 20px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
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
        <div class="bgc-white p-20 bd">
            <form method="post" action="{{ url('modelSalesReport') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3" style="padding: 0px 5px;">
                        <label>BP</label>
                        <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-3" style="padding: 0px 5px;">
                        <label>Retailer</label>
                        <input type="text" id="retailer_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='retailer_id' name="retailer_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-2" style="padding: 0px 5px;">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepicker" name="start_date">
                    </div>

                    <div class="form-group col-md-2" style="padding: 0px 5px;">
                        <label>End Date</label>
                        <input type="text" class="form-control datepicker" name="end_date">
                    </div>

                    <div class="col-md-2 mb-3" style="padding: 0px 5px;">
                        <button type="submit" class="btn cur-p btn-primary btn-block" style="margin-top: 30px;">Search</button>
                    </div>
                    {{-- 
                    <div class="form-group col-md-4">
                        <label for="inputState">Select Model</label>
                        <select class="form-control select2" name="model_name">
                        <option value="" selected="">Select Model</option>
                        @if(isset($productModelList))
                            @foreach($productModelList as $row)
                                <option value="{{ $row->product_model }}">{{ $row->product_model }}</option>
                            @endforeach
                        @endif
                        </select>
                    </div>
                    <div class="col-md-2 mb-3" style="padding: 0px 5px;">
                        <button type="submit" class="btn cur-p btn-primary btn-block" style="margin-top: 30px;">Search</button>
                    </div> 
                    --}}
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
            <div class="table-responsive">
                <table id="dataExport"  class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="product_model" style="cursor: pointer;">Model Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="saleQty" style="cursor: pointer;">Total Sale Qty</th>
                            <th>Sale Details</th>
                        </tr>
                    </thead>
                    <tbody>
                    @include('admin.report.product_sales_result_data')
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
<div class="modal fade" id="viewProductSalesDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>IMEI</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Msrp</th>
                                        <th>Msdp</th>
                                        <th>Mrp</th>
                                        <th>Sale</th>
                                        <th>Qty</th>
                                        <th>BP Info</th>
                                        <th>Retailer Info</th>
                                        <th>Sale Date</th>
                                    </tr>
                                </thead>
                                <tbody id="itemList">
                                    
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