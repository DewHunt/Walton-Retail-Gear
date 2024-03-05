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
</style>
<h4 class="c-grey-900 mB-20">Promo Offer List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddOfferModal" style="margin-left: 5px">Add Offer</button>
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
                <th>Photo</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="title" style="cursor: pointer;">Title</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="sdate" style="cursor: pointer;">Start Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="edate" style="cursor: pointer;">End Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="zone" style="cursor: pointer;">Zone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.offer.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>



<!--Add Modal Start -->
<div class="modal fade" id="AddOfferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Promo Offer</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddOffer" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Offer For <span class="required">*</span></label>
                                <select class="form-control"  style="width: 100%;" name="offer_for" required="">
                                    <option value="">Select</option>
                                    <option value="all" selected="selected">All</option>
                                    <option value="bp">BP</option>
                                    <option value="retailer">Retailer</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Zone</label>
                                <select class="select2" multiple="multiple" data-placeholder="Select a Incentive Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="zone" name="zone[]">
                                    <option value="">Select Zone</option>
                                    <option value="all">All</option>
                                    @if(isset($zoneList))
                                    @foreach($zoneList as $row)
                                    <option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Start Date <span class="required">*</span></label>
                                <input type="text" name="sdate" class="form-control datepicker" required=""/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>End Date <span class="required">*</span></label>
                                <input type="text" name="edate" class="form-control datepicker Number" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Offer Pic <span class="required">*</span></label><br/>
                                <span class="text-danger offer-pic-error"></span>
                                <input type="file" name="offer_pic" class="form-control" required=""/>
                                <p>Offer Banner Size Should Be: 600px X 600px</p>
                            </div>

                            <div class="col-md-6" style="margin-top:10px">
                                <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <br/>
                                <label>
                                    <input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add Modal End -->


<!--Edit & Update Modal Start -->
<div class="modal fade" id="editOfferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Offer Information</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateOffer" enctype="multipart/form-data">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Offer For <span class="required">*</span></label>
                                <select class="form-control" style="width: 100%;" name="offer_for" required="">
                                    <option value="">Select</option>
                                    <option value="all" class="uall">All</option>
                                    <option value="bp" class="ubp">BP</option>
                                    <option value="retailer" class="uretailer">Retailer</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Zone</label>
                                <select class="select2 uzone" multiple="multiple" data-placeholder="Select a Incentive Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="update_zone" name="zone[]">
                                    <option value="">Select Zone</option>
                                    <option value="all">All</option>
                                    @if(isset($zoneList))
                                    @foreach($zoneList as $row)
                                    <option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Start Date <span class="required">*</span></label>
                                <input type="text" name="sdate" class="form-control datepicker usdate" required=""/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>End Date <span class="required">*</span></label>
                                <input type="text" name="edate" class="form-control datepicker uedate" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Offer Pic <span class="required">*</span></label><br/>
                                <span class="text-danger offer-pic-error"></span>
                                <input type="file" name="offer_pic" class="form-control"/>
                                <p>Offer Banner Size Should Be: 600px x 600px</p>
                                <span id="img-tag"></span>
                            </div>

                            <div class="col-md-6" style="margin-top:10px">
                                <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <br/>
                                <label>
                                    <input type="radio" id="option1" name="status" value="1"> Active
                                </label>  &nbsp;&nbsp; 
                                <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
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