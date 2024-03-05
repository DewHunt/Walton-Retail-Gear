@extends('admin.master.master')
@section('content')
<style>
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
    }
     @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
     .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
    }
</style>

<div class="masonry-item col-md-12">

    @include('admin.incentive.menu')

    
    <div class="bgc-white p-20 bd">
        @if($IncentiveInfo['incentive_group'] ==1)
            <h6 class="c-grey-900">Brand Promoter Incentive Update</h6>
        @else
            <h6 class="c-grey-900">Retailer Incentive Update</h6>
        @endif
        <p style="font-size:12px"><b>Note</b>: All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.</p>
        <div class="mT-30">
            <form role="form" method="post" action="{{url('incentive.update', \Crypt::encrypt($IncentiveInfo['id']))}}" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put" />
            @csrf
            <input type="hidden" name="incentive_group" value="{{ $IncentiveInfo['incentive_group'] }}">


            <div class="form-group row {{ $errors->has('incentive_category') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Incentive Categories<span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select class="select2" data-placeholder="Select Incentive Category" style="width: 100%;" id="incentive_category" name="incentive_category" required="">
                        <option value="" selected="">Select Category</option>
                        <option value="general" @if($IncentiveInfo['incentive_category'] == "general") selected="selected" @endif>General</option>
                        <option value="target" @if($IncentiveInfo['incentive_category'] == "target") selected="selected" @endif>Target</option>
                    </select>
                </div>
                <span class="text-danger">{{ $errors->first('incentive_category') }}</span>
            </div>
            
            <div class="form-group row {{ $errors->has('incentive_title') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Incentive Title <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control" id="incentive_title" name="incentive_title" placeholder="Incentive Title" value="{{ $IncentiveInfo['incentive_title'] }}" required=""/>
                    <span class="text-danger">{{ $errors->first('incentive_title') }}</span>
                </div>
            </div>


            <div class="form-group row {{ $errors->has('product_model') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Model <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select class="select2" multiple="multiple" data-placeholder="Select a Model" data-dropdown-css-class="select2-purple" style="width: 100%;" id="product_model" name="product_model[]" required="">
                        <option value="">Select Model</option>
                        <option value="all" @if($ModelStatus == 1) selected="selected" @endif>All</option>
                        @if(isset($modelList))
                            @foreach($modelList as $row)
                                <option value="{{ $row['product_master_id'] }}" @if(in_array($row['product_master_id'],$productNameList)) selected="selected" @endif>{{ $row['product_model'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="text-danger">{{ $errors->first('product_model') }}</span>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">
                    Group Categories<span class="required">*</span>
                </label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select class="select2" data-placeholder="Select Category" style="width: 100%;" name="group_category_id" required="">
                        <option value="">Select Category</option>
                        @if(isset($CategoryList))
                            @foreach($CategoryList as $row)
                                <option value="{{ $row->id }}" @if($IncentiveInfo['group_category_id'] == $row->id) selected="selected" @endif>{{ $row->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="text-danger">{{ $errors->first('group_category_id') }}</span>
            </div>

            <div class="form-group row {{ $errors->has('incentive_type') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Incentive Type <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select class="select2" multiple="multiple" data-placeholder="Select a Incentive Type" data-dropdown-css-class="select2-purple" style="width: 100%;" id="incentive_type" name="incentive_type[]" required="">
                        <option value="">Select Type</option>
                        @if($IncentiveInfo['incentive_group'] == 2)
                        <option value="all" @if($TypeStatus == 1) selected="selected" @endif>All</option>
                            @if(isset($retailerList))
                                @foreach($retailerList as $row)
                                    <option value="{{ $row->id }}" @if(in_array($row->id,$iNcentiveName)) selected="selected" @endif>{{ trim($row->retailer_name) }}-{{ $row->phone_number }}</option>
                                @endforeach
                            @endif
                        @else

                        <option value="all" @if(in_array('all',$iNcentiveName)) selected="selected" @endif>All</option>
                        <option value="bp" @if(in_array('bp',$iNcentiveName)) selected="selected" @endif>BP</option>
                        <option value="csm" @if(in_array('csm',$iNcentiveName)) selected="selected" @endif>CSM</option>
                        <option value="monitor" @if(in_array('monitor',$iNcentiveName)) selected="selected" @endif>Monitor</option>
                        <option value="supervisior" @if(in_array('supervisior',$iNcentiveName)) selected="selected" @endif>Supervisior</option>

                        @endif

                    </select>
                </div>
                <span class="text-danger">{{ $errors->first('incentive_type') }}</span>
            </div>

            <div class="form-group row {{ $errors->has('zone') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Zone <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select class="select2" multiple="multiple" data-placeholder="Select a Incentive Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="zone" name="zone[]" required="">
                        <option value="">Select Zone</option>
                        <option value="all" @if($ZoneStatus == 1) selected="selected" @endif>All</option>
                        @if(isset($zoneList))
                            @foreach($zoneList as $row)
                                <option value="{{ $row['id'] }}" @if(in_array($row['id'],$zoneIdList)) selected="selected" @endif>{{ $row['zone_name'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="text-danger">{{ $errors->first('zone') }}</span>
            </div>

            <div class="form-group row {{ $errors->has('incentive_amount') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Incentive Amount <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="number" min="0" step="0.01" class="form-control" id="incentive_amount" name="incentive_amount" placeholder="Incentive Amount" value="{{ $IncentiveInfo['incentive_amount'] }}" required=""/>
                    <span class="text-danger">{{ $errors->first('incentive_amount') }}</span>
                </div>
            </div>

            <div class="form-group row {{ $errors->has('min_qty') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Min Qty <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="number" min="1" class="form-control" id="min_qty"  name="min_qty" value="{{ $IncentiveInfo['min_qty'] }}" placeholder="Min Qty" required=""/>
                    <span class="text-danger">{{ $errors->first('min_qty') }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Applicable From <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control datepicker" id="start_date"  name="start_date" placeholder="Applicable Date Ex:2021-01-01" value="{{ $IncentiveInfo['start_date'] }}" required=""/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Applicable End <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control datepicker" id="end_date"  name="end_date" placeholder="Applicable Date Ex:2021-01-30" value="{{ $IncentiveInfo['end_date'] }}" required=""/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Status <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <label>
                        <input type="radio" name="status" @if($IncentiveInfo['status'] == 1) checked="checked" @endif  value="1"> Active
                    </label>  &nbsp;&nbsp; 
                    <label>
                        <input type="radio" name="status" value="0" @if($IncentiveInfo['status'] == 0) checked="checked" @endif> In-Active
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection