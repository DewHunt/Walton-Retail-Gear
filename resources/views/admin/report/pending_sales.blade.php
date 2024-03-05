@extends('admin.master.master')
@section('content')
<style type="text/css">
    .table-responsive {
        display: inline-table !important;
        width: 100%;
    }
    .table-bordered th {
        border: 1px solid #e9ecef;
        font-size: 12px !important;
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .table-bordered th {
            font-size: 1.5rem !important;
            line-height: 1.2;
        }
         .new-bp-css .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
            margin: 0;
        }
        .status-css {
            margin: 0 !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) {
        .table-bordered th {
            font-size: 1.5rem !important;
            line-height: 1.2;
        }
        .new-bp-css .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
            margin: 0;
        }
        .status-css {
            margin: 0 !important;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .new-bp-css .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
            margin: 0;
        }
        .status-css {
            margin: 0 !important;
        }
    }
</style>
<h4 class="c-grey-900 mB-20">Pending Order List</h4>
<div class="row">
    <div class="masonry-item col-md-12 mY-10">
        <div class="bgc-white p-20 bd new-bp-css">
            @if(isset($saleList))
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Customer Name</th>
                            <th>Customer Phone</th>
                            <th>Sale Date</th>
                            <th>Order Status</th>
                            <th>Order Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($saleList as $row)
                        <tr>
                            <td>{{ ++$loop->index }}.</td>
                            <td>{{ $row->customer_name }}</td>
                            <td>{{ $row->customer_phone }}</td>
                            <td>{{ $row->sale_date }}</td>
                            <td>
                                <input data-id="{{ $row->id }}" class="pending-order-toggle-class" type="checkbox" data-onstyle="danger" data-offstyle="success" data-toggle="toggle" data-on="Pending" data-off="Complete" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" data-id="{{ $row->id }}" id="viewOrderDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewOrderDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
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


<!--View Product Modal Start -->
<div class="modal fade" id="viewOrderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body ">
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="salesInfo">
                        </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Photo</th>
                                        <th>IMEI Number</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Msrp Price</th>
                                        <th>Sale Price</th>
                                        <th>Sale Qty</th>
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
                <input type="hidden" name="sale_id" id="saleId">
                <button type="button" class="btn btn-primary" id="salesReturn">Sales Return</button>
                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div>
<!--View Product Modal End -->
@endsection