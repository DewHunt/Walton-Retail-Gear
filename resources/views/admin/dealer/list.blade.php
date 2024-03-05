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
<h4 class="c-grey-900 mB-20">Dealer Information</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddDeallerModal" style="margin-left: 5px">Add New Dealer</button>

            <button  style="display: none" type="button" class="btn btn-info pull-right btn-sm" onclick="ClickAddToDealerFormApi()">Add to Dealer From Api</button>
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
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;width:5px">Sl. <span id="id_icon"></span></th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_code" style="cursor: pointer;width:10px">Dealer Code </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="alternet_code" style="cursor: pointer;width:5px">Alternet Code </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer;width:15px">Dealer Name </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_address" style="cursor: pointer;width:15px">Address </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_phone_number" style="cursor: pointer;width:5px">Phone </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="rsm" style="cursor: pointer;width:5px">RSM </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;width:5px">Status </th>
                <th style="width:5px">Action </th>
            </tr>
            </tr>
        </thead>
        <tbody>
            @include('admin.dealer.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>
<!--Add New Dealer Modal Start -->
<div class="modal fade" id="AddDeallerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Dealer</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="{{route('dealer.add')}}" id="AddDealer">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" id="search_dealer_code" placeholder="Search Dealer">
                        </div>
                        <div class="form-group col-md-4">
                            <button type="button" class="btn btn-primary btn-block" id="search_dealer_button">Search</button>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Code <span class="required">*</span></label>
                            <input type="text" name="dealer_code" class="form-control apidcode" placeholder="Dealer Code" required=""/>
                            <span class="text-danger">
                                <strong id="dealer-code-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Alternate Code</label>
                            <input type="text" name="alternate_code" class="form-control apialtercode" placeholder="Alternate  Code"/>
                            <span class="text-danger">
                                <strong id="alternet-code-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Name <span class="required">*</span></label>
                            <input type="text" name="dealer_name" class="form-control apidname" placeholder="Dealer Name" required=""/>
                            <span class="text-danger">
                                <strong id="name-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Zone <span class="required">*</span></label>
                            <input type="text" name="zone" class="form-control apidzone" placeholder="Dealer Zone" required=""/>
                            <span class="text-danger">
                                <strong id="zone-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" placeholder="Dealer City"/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Division</label>
                            <input type="text" name="division" class="form-control" placeholder="Dealer Division"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Mobile <span class="required">*</span></label>
                            <input type="text" maxlength="11"  minlength="11" name="dealer_phone_number" class="form-control apidphone Number" placeholder="Mobile Number" required=""/>
                            <span class="text-danger">
                                <strong id="phone-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Dealer Type</label>
                            <input type="text" name="dealer_type" class="form-control" placeholder="Dealer Type"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Address</label>
                            <textarea class="form-control apidaddress" name="dealer_address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="dealer_id" class="apidealerid"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Dealer Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editDelarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Information</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateDealer">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Code <span class="required">*</span></label>
                            <input type="text" name="dealer_code" class="form-control dealercode" placeholder="Dealer Code" required=""/>
                            <span class="text-danger">
                                <strong id="updatedealer-code-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Alternate Code</label>
                            <input type="text" name="alternate_code" class="form-control alternetcode" placeholder="Alternate  Code"/>
                            <span class="text-danger">
                                <strong id="update-alternet-code-error"></strong>
                            </span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Name <span class="required">*</span></label>
                            <input type="text" name="dealer_name" class="form-control dealername" placeholder="Dealer Name" required=""/>
                            <span class="text-danger">
                                <strong id="update-name-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Zone <span class="required">*</span></label>
                            <input type="text" name="zone" class="form-control zone" placeholder="Dealer Zone" required=""/>
                            <span class="text-danger">
                                <strong id="update-zone-error"></strong>
                            </span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>City</label>
                            <input type="text" name="city" class="form-control city" placeholder="Dealer City"/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Division</label>
                            <input type="text" name="division" class="form-control division" placeholder="Dealer Division"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Mobile <span class="required">*</span></label>
                            <input type="text" maxlength="11"  minlength="11" name="dealer_phone_number" class="form-control dealerphone Number" placeholder="Mobile Number" required=""/>
                            <span class="text-danger">
                                <strong id="update-phone-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Dealer Type</label>
                            <input type="text" name="dealer_type" class="form-control dealertype" placeholder="Dealer Type"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Address</label>
                            <textarea class="form-control dealeraddress" name="dealer_address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="dealer_id" class="dealerid"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection

