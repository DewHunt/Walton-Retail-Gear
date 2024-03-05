@extends('admin.master.master')
@section('css')
<style type="text/css">
.my-custom-scrollbar {
    position: relative;
    height: 500px;
    overflow: auto;
}
.table-wrapper-scroll-y {
    display: block;
}
.subBtnheight {
    height:50px;
}
.cfmbpselectbox {
    width: 35% !important;
    height: 30px !important;
    font-size: 16px;
}

@media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .my-custom-scrollbar {
            position: relative;
            height: 1200px;
            overflow: auto;
        }
        .subBtnheight {
            height:70px;
        }
        .cfmbpselectbox {
            width: 60%;
            height: 58px !important;
            font-size: 24px;
        }
        .bgc-white {
            height: 150px !important;
        }
        .p-20 {
            padding: 20px 0px !important;
        }
    }
/* Portrait and Landscape */
@media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) {
        .my-custom-scrollbar {
            position: relative;
            height: 1200px;
            overflow: auto;
        }
        .subBtnheight {
            height:70px;
        }
        .cfmbpselectbox {
            width: 60%;
            height: 58px !important;
            font-size: 24px;
        }
        .bgc-white {
            height: 150px !important;
        }
        .cp-20 {
            padding: 20px 0px !important;
        }
        .col-md-12 {
            padding-right:  0px !important;
        }
    }

@media (min-width: 768px) and (max-width: 1024px) {
    .my-custom-scrollbar {
        position: relative;
        height: 850px;
        overflow: auto;
    }
    .subBtnheight {
        height:70px;
    }
    .cfmbpselectbox {
        width: 60%;
        height: 58px !important;
        font-size: 24px;
    }
    .bgc-white {
        height: 150px !important;
    }
    .cp-20 {
        padding: 20px 0px !important;
    }
    .col-md-12 {
        padding-right:  0px !important;
    }
}

</style>
@endsection
@section('content')
<h4 class="c-grey-900">Focus Model To BP</h4>
@php $groupId = (Session::get('catId')) ? Session::get('catId'):1; @endphp
<div class="masonry-item col-md-12" style="padding-left:0px !important;padding-right: 0px !important;">
    <div class="bgc-white p-10 bd">
        <form method="post" action="{{ route('bpromoter.focus_model_to_bp_save') }}">
        @csrf
        <div class="col-md-6" style="padding-left:0px !important">
            <label class="control-label">Select Category: <span class="required">*</span></label>
            <select class="form-control cfmbpselectbox" name="category_id" id="category_id" required="">
            <option value="">Select Category</option>
                @foreach($categoryLists as $cat)
                    <option value="{{ $cat->id }}" @if($cat->id == $groupId) selected="selected"@endif>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <br/>
        <input type="hidden" class="catId" value="{{ $catId }}">
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-striped table-bordered" width="100%">
                <thead>
                    <tr>
                        <th>Sl.</th>
                        <th>Model</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($productModelLists) && !empty($productModelLists))
                        @foreach($productModelLists as $key=>$row)
                        <tr>
                            @php
                            $getStockInfo = checkModelStockByBP($groupId,$row->product_master_id,$row->product_model);
                            
                            $green = 0;$yellow = 0; $red = 0;
                            if(!empty($getStockInfo)) {
                                $pmId   = $getStockInfo->product_master_id;
                                $green  = $getStockInfo->green;
                                $yellow = $getStockInfo->yellow;
                                $red    = $getStockInfo->red;
                                //echo "<pre>";print_r($getStockInfo);echo "</pre>";
                            }
                            @endphp
                            <th scope="row">{{ ++$loop->index }}.</th>
                            <td>
                                <input class="mobileCheckBox" type="checkbox" id="selct_model" name="select_model[]" value="{{ $row->product_master_id }}" @if(!empty($getStockInfo) && $row->product_master_id == $pmId) checked="checked" @endif/> {{ $row->product_model }}
                            </td>
                            <td class="Td-input">
                                <input type="hidden" name="product_master_id[{{ $row->product_master_id }}]" value="{{ $row->product_master_id }}">
                                <input type="hidden" name="product_id[{{ $row->product_master_id }}]" value="{{ $row->product_id }}">
                                <input type="hidden" name="model_name[{{ $row->product_master_id }}]" value="{{ $row->product_model }}">
                                
                                <label>Green:</label> 
                                <input class="color-input-padd" type="number" name="green[{{ $row->product_master_id }}]" minlength="0" style="padding:0px 5px" value="{{ $green }}">
                                <label>Yellow:</label> 
                                <input class="color-input-padd" type="number" name="yellow[{{ $row->product_master_id }}]" minlength="0" style="padding:0px 5px" value="{{ $yellow }}">
                                <label>Red:</label> 
                                <input class="color-input-padd" type="number" name="red[{{ $row->product_master_id }}]"  minlength="0" style="padding:0px 5px" value="{{ $red }}">
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-12 bgc-white cp-20 subBtnheight">
            <button type="submit" class="btn btn-primary btn-color pull-right mT-10">Submit</button>
        </div>
        </form>
    </div>
</div>
@endsection