@extends('admin.master.master')
@section('content')
<style>
.top-margin {
    margin-top: 1rem;
}
.cp {
    padding:5px
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
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3){
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Current Pre-Booking List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-info pull-right btn-sm" style="margin-left:5px; display: none;">Current Pre-Booking</button>  

            <a href="{{ route('prebooking.expire') }}">
                <button  type="button" class="btn btn-success pull-right btn-sm" style="margin-left:5px;">Expire Pre-Booking</button>
            </a>  

            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddPreBookingModal" style="margin-left:5px;">Add Pre-Booking</button>  
        </div>
    </div>
</div>

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
                <th class="sorting" data-sorting_type="asc" data-column_name="model" style="cursor: pointer;">Model</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="color" style="cursor: pointer;">Color</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="end_date" style="cursor: pointer;">End Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="minimum_advance_amount" style="cursor: pointer;">MiniMum Advanced Amount</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="max_qty" style="cursor: pointer;">Max Qty</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="price" style="cursor: pointer;">Price</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th style="width:10px">Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.prebooking.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Add New PreBooking Modal Start -->
<div class="modal fade" id="AddPreBookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Pre-Booking</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="{{route('prebooking.add')}}" id="AddProductPreBooking">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Model <span class="required">*</span></label>
                            <input type="text" name="model" class="form-control" required=""/>
                            <span class="text-danger">
                                <strong id="model-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Color <span class="required">*</span></label>
                            <input type="text" name="color" class="form-control"/>
                            <span class="text-danger">
                                <strong id="color-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Start Date <span class="required">*</span></label>
                            <input type="text" name="start_date" class="form-control datepicker"/>
                            <span class="text-danger">
                                <strong id="start-date-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>End Date <span class="required">*</span></label>
                            <input type="text" name="end_date" class="form-control datepicker" required=""/>
                            <span class="text-danger">
                                <strong id="end-date-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>MiniMum Advanced Amount <span class="required">*</span></label>
                            <input type="number" name="minimum_advance_amount" class="form-control" required=""/>
                            <span class="text-danger">
                                <strong id="minimum-amount-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Maximum Qty <span class="required">*</span></label>
                            <input type="number" name="max_qty" class="form-control" required=""/>
                            <span class="text-danger">
                                <strong id="max-qty-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Price <span class="required">*</span></label>
                            <input type="number" name="price" class="form-control"/>
                            <span class="text-danger">
                                <strong id="price-error"></strong>
                            </span>
                        </div>

                        <div class="col-md-6 mb-5" style="margin-top: 15px;">
                            <label>Status <span class="required">*</span></label><br/>
                            <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                            <label><input type="radio" name="status" value="0"> In-Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="product_id" class="ApiproductId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Pre Booking Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editPreBookingModal" tabindex="-2" role="dialog" aria-labelledby="editPreBookingModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModal">Update Pre-Booking</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateProductPreBooking" >
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Model <span class="required">*</span></label>
                            <input type="text" name="model" class="form-control getModel" required=""/>
                            <span class="text-danger">
                                <strong id="update-model-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Color <span class="required">*</span></label>
                            <input type="text" name="color" class="form-control getColor"/>
                            <span class="text-danger">
                                <strong id="update-color-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Start Date <span class="required">*</span></label>
                            <input type="text" name="start_date" class="form-control datepicker getSdate"/>
                            <span class="text-danger">
                                <strong id="update-start-date-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>End Date <span class="required">*</span></label>
                            <input type="text" name="end_date" class="form-control datepicker getEdate" required=""/>
                            <span class="text-danger">
                                <strong id="update-end-date-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>MiniMum Advanced Amount <span class="required">*</span></label>
                            <input type="number" name="minimum_advance_amount" class="form-control getMiniMumAmount" required=""/>
                            <span class="text-danger">
                                <strong id="update-minimum-amount-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Maximum Qty <span class="required">*</span></label>
                            <input type="number" name="max_qty" class="form-control getMaxQty" required=""/>
                            <span class="text-danger">
                                <strong id="update-max-qty-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Price <span class="required">*</span></label>
                            <input type="number" name="price" class="form-control getPrice"/>
                            <span class="text-danger">
                                <strong id="update-price-error"></strong>
                            </span>
                        </div>

                        <div class="col-md-6 mb-5" style="margin-top: 15px;">
                            <label>Status <span class="required">*</span></label><br/>
                            <label><input type="radio" id="option1" name="status" value="1"> Active</label>  &nbsp;&nbsp; 
                            <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update_id" id="updateId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection

