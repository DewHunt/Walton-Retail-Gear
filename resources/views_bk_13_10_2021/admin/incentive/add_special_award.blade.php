@extends('admin.master.master')
@section('content')
<style>
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .col-sm-2 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 25% !important;
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        .col-sm-6 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 75% !important;
            flex: 0 0 75% !important;
            max-width: 75% !important;
        }
        .select2-container .select2-selection--single {
            height: 60px;
        }
        .select2-container{
            width: 100% !important;
        }
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .col-sm-2 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 25% !important;
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        .col-sm-6 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 75% !important;
            flex: 0 0 75% !important;
            max-width: 75% !important;
        }
        .select2-container .select2-selection--single {
            height: 60px;
        }
        .select2-container{
            width: 100% !important;
        }
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .col-sm-2 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 25% !important;
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        .col-sm-6 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 75% !important;
            flex: 0 0 75% !important;
            max-width: 75% !important;
        }
        .select2-container .select2-selection--single {
            height: 60px;
        }
        .select2-container{
            width: 100% !important;
        }
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
    }

</style>
<div class="masonry-item col-md-12">
    @include('admin.incentive.menu')
    <div class="bgc-white p-20 bd">
        @if(isset($groupId) && !empty($groupId) && $groupId == 1)
        <h6 class="c-grey-900">BP Special Award</h6>
        @else
        <h6 class="c-grey-900">Retailer Special Award</h6>
        @endif
        <div class="mT-30">
            <form method="POST" action="{{ url('award.add') }}">
                @csrf
                <input type="hidden" name="award_group" value="{{ $groupId }}">

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Special Award Title</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="award_title" name="award_title" placeholder="Award Title" required="required"/>
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('product_model') ? 'has-error' : '' }}">
                    <label class="col-sm-2 col-form-label">Model <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <select class="select2" multiple="multiple" data-placeholder="Select a Model" data-dropdown-css-class="select2-purple" style="width: 100%;" id="product_model" name="product_model[]" required="">
                            <option value="0">Select Model</option>
                            <option value="all">All</option>
                            @if(isset($modelList))
                            @foreach($modelList as $row)
                            <option value="{{ $row->product_master_id }}">{{ $row->product_model }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <span class="text-danger">{{ $errors->first('product_model') }}</span>
                </div>

                <div class="form-group row {{ $errors->has('incentive_type') ? 'has-error' : '' }}">
                    <label class="col-sm-2 col-form-label">Incentive Type <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <select class="select2" multiple="multiple" data-placeholder="Select a Incentive Type" data-dropdown-css-class="select2-purple" style="width: 100%;" id="incentive_type" name="incentive_type[]" required>
                            @if(isset($groupId) && !empty($groupId) && $groupId == 1)
                            <option value="0">Select Type</option>
                            <option value="all">All</option>
                            <option value="bp">BP</option>
                            <option value="csm">CSM</option>
                            <option value="monitor">Monitor</option>
                            <option value="supervisior">Supervisior</option>
                            @else
                            @if(isset($retailerList))
                            @foreach($retailerList as $row)
                            <option value="{{ $row->retailer_id }}">{{ trim($row->retailer_name) }} -{{ $row->phone_number }}</option>
                            @endforeach
                            @endif
                            @endif    
                        </select>
                    </div>
                    <span class="text-danger">{{ $errors->first('incentive_type') }}</span>
                </div>

                <div class="form-group row {{ $errors->has('zone') ? 'has-error' : '' }}">
                    <label class="col-sm-2 col-form-label">Zone <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <select class="select2" multiple="multiple" data-placeholder="Select a Incentive Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="zone" name="zone[]" required="">
                            <option value="">Select Zone</option>
                            <option value="all">All</option>
                            @if(isset($zoneList))
                            @foreach($zoneList as $row)
                            <option value="{{ $row->id }}">{{ $row->zone_name }}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="text-danger">{{ $errors->first('zone') }}</span>
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('award_type') ? 'has-error' : '' }}">
                    <label class="col-sm-2 col-form-label">Award Type <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <select id="award_type" name="award_type" class="form-control select2" style="width: 567.5px;">
                            <option value="">Select Type</option>
                            <option value="cash">Cash</option>
                            <option value="gift">Gift</option>
                            <option value="package">Package</option>
                        </select>
                        <span class="text-danger">{{ $errors->first('award_type') }}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">RemuneRation</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="remune_ration" name="remune_ration"/>
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('min_qty') ? 'has-error' : '' }}">
                    <label class="col-sm-2 col-form-label">Min Qty <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <input type="number" min="1" class="form-control" id="min_qty"  name="min_qty" placeholder="Min Qty" />
                        <span class="text-danger">{{ $errors->first('min_qty') }}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Applicable From <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control datepicker" id="start_date"  name="start_date" placeholder="Applicable Date Ex:2021-01-01"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Applicable End <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control datepicker" id="end_date"  name="end_date" placeholder="Applicable Date Ex:2021-01-30" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                        <label><input type="radio" name="status" value="0"> In-Active</label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection