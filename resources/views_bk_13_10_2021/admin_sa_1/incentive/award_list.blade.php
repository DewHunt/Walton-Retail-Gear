@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Retailer Special Award List</h4>

@include('admin.incentive.menu')

<div class="row">
    @if(isset($groupId) && !empty($groupId) && $groupId == 1)
        <div class="col-md-9"> Brand Promoter Special Award List</div>
    @else
        <div class="col-md-9"> Retailer Special Award List</div>
    @endif
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="serach" id="serach" class="form-control" />
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="award_title" style="cursor: pointer;">Award Title</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="award_type" style="cursor: pointer;">Award Type</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="min_qty" style="cursor: pointer;">Qty</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="end_date" style="cursor: pointer;">End Date</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @include('admin.incentive.award_result_data')
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>
@endsection