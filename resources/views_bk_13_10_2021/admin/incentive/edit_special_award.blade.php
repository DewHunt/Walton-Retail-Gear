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
    @media (min-width: 768px) and (max-width: 1024px) {
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
    }
</style>

<div class="masonry-item col-md-12">
    
@include('admin.incentive.menu')
    
    <div class="bgc-white p-20 bd">
        @if($AwardInfo['award_group'] == 1)
            <h6 class="c-grey-900">BP Incentive Award Update</h6>
        @else
            <h6 class="c-grey-900">Retailer Award Update</h6>
        @endif
        <p style="font-size:12px"><b>Note</b>: All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.</p>
        <div class="mT-30">
            <form method="POST" action="{{ url('award.update') }}">
            @csrf
            <input type="hidden" name="award_id" value="{{ $AwardInfo['id'] }}">
            <input type="hidden" name="award_group" value="{{ $AwardInfo['award_group'] }}">
            
            <div class="form-group row {{ $errors->has('award_title') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Award Title <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control" id="award_title" name="award_title" placeholder="Award Title" value="{{ $AwardInfo['award_title'] }}" required=""/>
                    <span class="text-danger">{{ $errors->first('award_title') }}</span>
                </div>
            </div>


            <div class="form-group row {{ $errors->has('product_model') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Model <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9 select-h">
                    <select class="select2" multiple="multiple" data-placeholder="Select a Model" data-dropdown-css-class="select2-purple" style="width: 100%;" id="product_model" name="product_model[]" required="">
                        <option value="">Select Model</option>
                        <option value="all"  @if($ModelStatus == 1) selected="selected" @endif>All</option>
                        @if(isset($modelList))
                            @foreach($modelList as $row)
                                <option value="{{ $row['product_master_id'] }}" @if(in_array($row['product_master_id'],$productNameList)) selected="selected" @endif>{{ $row['product_model'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="text-danger">{{ $errors->first('product_model') }}</span>
            </div>

            <div class="form-group row {{ $errors->has('incentive_type') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Incentive Type <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select class="select2" multiple="multiple" data-placeholder="Select a Incentive Type" data-dropdown-css-class="select2-purple" style="width: 100%;" id="incentive_type" name="incentive_type[]" required="">

                        @if($AwardInfo['award_group'] == 1)
                            <option value="">Select Type</option>
                            <option value="all" @if(in_array('all',$iNcentiveName)) selected="selected" @endif>All</option>
                            <option value="bp" @if(in_array('bp',$iNcentiveName)) selected="selected" @endif>BP</option>
                            <option value="csm" @if(in_array('csm',$iNcentiveName)) selected="selected" @endif>CSM</option>
                            <option value="monitor" @if(in_array('monitor',$iNcentiveName)) selected="selected" @endif>Monitor</option>
                            <option value="supervisior" @if(in_array('supervisior',$iNcentiveName)) selected="selected" @endif>Supervisior</option>
                        @else
                            @if(isset($retailerList))
                                @foreach($retailerList as $row)
                                    <option value="{{ $row->retailer_id }}" @if(in_array($row['retailer_id'],$awardIncentiveId)) selected="selected" @endif>{{ trim($row->retailer_name) }}-{{ $row->phone_number }}</option>
                                @endforeach
                            @endif
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

             <div class="select-h form-group row {{ $errors->has('award_type') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Award Type <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <select id="award_type" name="award_type" class="form-control select2" style="width: 100%;">
                        <option value="">Select Type</option>
                        <option value="cash" @if($AwardInfo['award_type'] == 'cash') selected="selected" @endif>Cash</option>
                        <option value="gift" @if($AwardInfo['award_type'] == 'gift') selected="selected" @endif>Gift</option>
                        <option value="package" @if($AwardInfo['award_type'] == 'package') selected="selected" @endif>Package</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('award_type') }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">RemuneRation</label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control" id="remune_ration" name="remune_ration" value="{{ $AwardInfo['remune_ration'] }}" />
                </div>
            </div>

            <div class="form-group row {{ $errors->has('min_qty') ? 'has-error' : '' }}">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Min Qty <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="number" min="1" class="form-control" id="min_qty"  name="min_qty" value="{{ $AwardInfo['min_qty'] }}" placeholder="Min Qty" required=""/>
                    <span class="text-danger">{{ $errors->first('min_qty') }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Applicable From <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control datepicker" id="start_date"  name="start_date" placeholder="Applicable Date Ex:2021-01-01" value="{{ $AwardInfo['start_date'] }}" required=""/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Applicable End <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <input type="text" class="form-control datepicker" id="end_date"  name="end_date" placeholder="Applicable Date Ex:2021-01-30" value="{{ $AwardInfo['end_date'] }}" required=""/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 bp-add-incentive-3 col-form-label">Status <span class="required">*</span></label>
                <div class="col-sm-6 bp-add-incentive-9">
                    <label>
                        <input type="radio" name="status" @if($AwardInfo['status'] == 1) checked="checked" @endif  value="1"> Active
                    </label>  &nbsp;&nbsp; 
                    <label>
                        <input type="radio" name="status" value="0" @if($AwardInfo['status'] == 0) checked="checked" @endif> In-Active
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary pull-right">Uppdate</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection