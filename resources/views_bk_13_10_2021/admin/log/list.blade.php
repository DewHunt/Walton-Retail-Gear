@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">User Log List</h4>
<div class="row">
    <div class="col-md-9"></div>
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
            <th class="sorting" data-sorting_type="desc" data-column_name="id" style="cursor: pointer;">Sl.</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="name" style="cursor: pointer;">Name</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="type" style="cursor: pointer;">Type</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="ip_address" style="cursor: pointer;">IP Address</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="created_at" style="cursor: pointer;">Login Time</th>
        </tr>
    </thead>
    <tbody>
        @include('admin.log.result_data')
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>
@endsection