@extends('admin.master.master')
@section('content')
@if(isset($groupId) && !empty($groupId) && $groupId == 1)
<h4 class="c-grey-900 mB-20">Brand Promoter Incentive List</h4>
@else
<h4 class="c-grey-900 mB-20">Retailer Incentive List</h4>
@endif

@include('admin.incentive.menu')
<style>
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 290px;
            height: 80px;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 290px;
            height: 80px;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 290px;
            height: 80px;
        }
    }
</style>

<div class="row">
    @if(isset($groupId) && !empty($groupId) && $groupId == 1)
    <div class="col-md-9">Brand Promoter Incentive List</div>
    @else
    <div class="col-md-9">Retailer Incentive List</div>
    @endif

    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="serach" id="serach" class="form-control"/>
        </div>
    </div>
</div>


<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_title" style="cursor: pointer;">Title</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_category" style="cursor: pointer;">Category</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_amount" style="cursor: pointer;">Amount</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="min_qty" style="cursor: pointer;">Qty</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="end_date" style="cursor: pointer;">End Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.incentive.incentive_result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>
@endsection