@extends('admin.master.master')
@section('content')
<style>
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
    .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
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
    .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
}
</style>
<h4 class="c-grey-900 mB-20">Brand Promoter</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddBPromoterModal" style="margin-left: 5px">Add BP</button> <button  type="button" class="btn btn-info pull-right btn-sm" onclick="AddAllPromoterByApi()" disabled="" style="display: none">Add to BP By Api</button>
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
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">Promoter Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;">Promoter Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.bpromoter.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Add New Modal Start -->
<div class="modal fade" id="AddBPromoterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Brand Promoter</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">
                    [** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddBPromoter">
                @csrf
                <div class="modal-body">
                    <div class="form-row" id="ApiSearchDiv">
                        <div class="form-group col-md-10">
                            <input type="text" class="form-control Number" id="search_bpromoter_phone" maxlength="11" placeholder="Search BP By Phone">
                        </div>

                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-primary btn-block" id="search_bpromoter_button">Search</button>
                        </div>
                    </div>

                    <div class="form-group"  style="display: none;">
                        <label>Brand Promoter ID</label>
                        <input type="text" name="bp_id" class="form-control ApiBPromoterId" placeholder="Enter Brand Promoter ID"/>
                    </div>

                    <div class="form-group"  style="display: none;">
                        <label>Retailer ID</label>
                        <input type="text" name="retailer_id" class="form-control ApiBPromoterRetailerId" placeholder="Enter Retailer ID"/>
                    </div>

                    <div class="col-md-12 retailer-select-h">
                        <div class="row">
                            <div class="col-md-4 mb-2" style="padding: 0px 7px 0px 15px;">
                                <label>Select Category <span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="bp_category" required="">
                                    <option value="">Select Category</option>
                                    @if(isset($CategoryList))
                                    @foreach($CategoryList as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4 mb-2" style="padding:0px">
                                <label>BP Name <span class="required">*</span></label>
                                <input type="text" name="bp_name" class="form-control ApiBPromoterName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="name-error"></strong>
                                </span>
                            </div>

                            <div class="col-md-4 mb-2" style="padding:0px 5px">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="text" name="bp_phone" maxlength="11"  minlength="11" class="form-control ApiBPromoterPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger">
                                    <strong id="phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2" style="margin-top:15px">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search_retailer_mobile" placeholder="Search Retailer By Phone">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary btn-block" type="button" id="search_retailer_button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Retailer Name <span class="required">*</span></label>
                                <input type="text" name="retailer_name" class="form-control ApiRetailerName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="name-error"></strong>
                                </span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Owner Name <span class="required">*</span></label>
                                <input type="text" name="owner_name" class="form-control ApiRetailerOwnerName" placeholder="Owner Name"required=""/>
                                <span class="text-danger">
                                    <strong id="owner-name-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Police Station</label>
                                <input type="text" name="police_station" class="form-control ApiRetailerPoliceStation" placeholder="Police Station" />
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="text" name="retailer_phone_number" maxlength="11"  minlength="11" class="form-control ApiRetailerPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger">
                                    <strong id="phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Retailer Address <span class="required">*</span></label>
                                <textarea name="retailder_address" class="form-control ApiRetailerAddress" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger">
                                    <strong id="address-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Distributor Code <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search_retailer_dealer_code" placeholder="Search Dealer By Code" required="">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary btn-block" type="button" id="search_retailer_dealer_button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2" style="display: none" id="dCode">
                                <label>Distributor Code <span class="required">*</span></label>
                                <input type="text" name="distributor_code" class="form-control dealerCode" placeholder="Distributor Code" required=""/>
                            </div>
                            <div class="col-md-6 mb-2" style="display: none" id="dAlternetCode">
                                <label>Distributor Alternet Code <span class="required">*</span></label>
                                <input type="text" name="distributor_code2" class="form-control dealerAlternetCode" placeholder="Alternet Code" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2" style="display: none" id="dName">
                                <label>Distributor Name <span class="required">*</span></label>
                                <input type="text" name="distributor_name" class="form-control dealerName" placeholder="Name" required=""/>
                            </div>
                            <div class="col-md-6 mb-2" style="display: none" id="dZone">
                                <label>Distributor Zone <span class="required">*</span></label>
                                <input type="text" name="distributor_zone" class="form-control dealerZone" placeholder="Zone" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Division Name</label>
                                <input type="text" name="division_name" class="form-control ApiRetailerDivisionName" placeholder="Division Name"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Distric Name</label>
                                <input type="text" name="distric_name" class="form-control ApiRetailerDistric" placeholder="Distric Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Payment Type <span class="required">*</span></label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="mfc" name="payment_type" value="1" onclick="checkPaymentType(1)"> MFC
                                    </label> &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" id="bank" name="payment_type" value="2" onclick="checkPaymentType(2)"> Bank Account
                                    </label>
                                </div>
                                <span class="paymentNumber"></span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" name="status" checked="checked" value="1"> Active
                                    </label>  &nbsp;&nbsp; 
                                    <label>
                                        <input type="radio" name="status" value="0"> In-Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="api_payment_type" class="ApiRetailerPaymentType"/>
                <input type="hidden" name="api_payment_number" class="ApiRetailerPaymentNumber"/>
                <input type="hidden" name="retailer_id" class="ApiRetailerId"/>
                <input type="hidden" name="zone_id" class="ApiRetailerZoneId"/>
                <input type="hidden" name="division_id" class="ApiRetailerDivisionId"/>
                <input type="hidden" name="distric_id" class="ApiRetailerDistricId"/>
                <input type="hidden" name="thana_id" class=" ApiRetailerThanaID"/>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Modal End -->


<!--Edit & Update Modal Start -->
<div class="modal fade" id="editBPromoterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Brand Promoter Information</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateBPromoter">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 retailer-select-h">
                        <div class="row">
                            <div class="col-md-4 mb-2" style="padding: 0px 7px 0px 15px;">
                                <label>Select Category <span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="bp_category" required="">
                                    <option value="">Select Category</option>
                                    @if(isset($CategoryList))
                                    @foreach($CategoryList as $row)
                                    <option value="{{ $row->id }}" class="UpdateApiCategoryId{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4 mb-2" style="padding:0px">
                                <label>BP Name <span class="required">*</span></label>
                                <input type="text" name="bp_name" class="form-control UpdateApiBPromoterName" placeholder="Enter Brand Promoter Name" required=""/>
                                <span class="text-danger">
                                    <strong id="update-name-error"></strong>
                                </span>
                            </div>
                            <div class="col-md-4 mb-2" style="padding:0px 5px">
                                <label>BP Phone <span class="required">*</span></label>
                                <input type="text" name="bp_phone" class="form-control UpdateApiBPromoterPhone Number" placeholder="Enter Brand Promoter Phone Number" maxlength="11"  minlength="11" required=""/>
                                <span class="text-danger">
                                    <strong id="update-phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Retailer Name <span class="required">*</span></label>
                                <input type="text" name="retailer_name" class="form-control UpdateApiRetailerName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="update-name-error"></strong>
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Owner Name <span class="required">*</span></label>
                                <input type="text" name="owner_name" class="form-control UpdateApiRetailerOwnerName" placeholder="Owner Name"required=""/>

                                <span class="text-danger">
                                    <strong id="update-owner-name-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Police Station</label>
                                <input type="text" name="police_station" class="form-control UpdateApiRetailerPoliceStation" placeholder="Police Station"/>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Retailer Phone Number <span class="required">*</span></label>
                                <input type="text" name="retailer_phone_number" maxlength="11"  minlength="11" class="form-control UpdateApiRetailerPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger">
                                    <strong id="update-phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Retailer Address <span class="required">*</span></label>
                                <textarea name="retailder_address" class="form-control UpdateApiRetailerAddress" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger">
                                    <strong id="update-address-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Distributor Code <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="distributor_code" class="form-control UpdateApiRetailerDistributorCode" id="usearch_retailer_dealer_code" placeholder="Search Dealer By Code" required="">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary" type="button" id="usearch_retailer_dealer_button"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--------- Start  ---->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3 mb-2" style="display: none" id="udAlternetCode">
                                <label>Alternet Code <span class="required">*</span></label>
                                <input type="text" name="distributor_code2" class="form-control UpdateApiRetailerDistributorCode2 udealerAlternetCode" placeholder="Alternet Distributor Code"/>
                            </div>

                            <div class="col-md-3 mb-2" style="display: none" id="udZone">
                                <label>Distributor Zone <span class="required">*</span></label>
                                <input type="text" name="distributor_zone" class="form-control udealerZone" placeholder="Zone" required=""/>
                            </div>

                            <div class="col-md-6 mb-2" style="display: none" id="udName">
                                <label>Distributor Name <span class="required">*</span></label>
                                <input type="text" name="distributor_name" class="form-control udealerName" placeholder="Name" required=""/>
                            </div>

                        </div>
                    </div>
                    <!--------- End  ---->

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Division Name</label>
                                <input type="text" name="division_name" class="form-control UpdateApiRetailerDivisionName" placeholder="Division Name"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Distric Name</label>
                                <input type="text" name="distric_name" class="form-control UpdateApiRetailerDistric" placeholder="Distric Name"/>
                            </div>
                        </div>
                    </div>

                    <!--------- Start  -------->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Payment Type <span class="required">*</span></label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="umfc" name="payment_type" value="1" onclick="checkPaymentType(1)"> MFC
                                    </label> &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" id="ubank" name="payment_type" value="2" onclick="checkPaymentType(2)"> Bank Account
                                    </label>
                                </div>
                                <span class="paymentNumber">
                                    <div class="agentDiv">
                                        <div class="form-group">
                                            <input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket" required=""/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber mfc_field" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))"  maxlength="11"  minlength="11" required=""/>
                                        </div>
                                    </div>

                                    <div class="bankDiv">
                                        <div class="form-group">
                                            <input type="text" name="bank_name" class="form-control UpdateBankName" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank" required=""/>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber bank_field" placeholder="Bank Payment Number"  minlength="11" required=""/>
                                        </div>
                                    </div>
                                </span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="option1" name="status" value="1"> Active
                                    </label>  &nbsp;&nbsp; 
                                    <label>
                                        <input type="radio" id="option2" name="status" value="0"> In-Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--------- End  -------->
                </div>
                <input type="hidden" id="UpdateApiRetailerPaymentType" disabled="disabled"/>
                <input type="hidden" id="UpdateApiRetailerPaymentNumber" disabled="disabled"/>
                <input type="hidden" name="retailer_id" class="UpdateApiRetailerId"/>
                <input type="hidden" name="zone_id" class="UpdateApiRetailerZoneId"/>
                <input type="hidden" name="division_id" class="UpdateApiRetailerDivisionId"/>
                <input type="hidden" name="distric_id" class="UpdateApiRetailerDistricId"/>
                <input type="hidden" name="thana_id" class=" UpdateApiRetailerThanaID"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection