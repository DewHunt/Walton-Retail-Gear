@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">RSM Information</h4>
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
            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="rsm" style="cursor: pointer;">Rsm</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="asm" style="cursor: pointer;">Asm</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="email_address" style="cursor: pointer;">Email</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="mobile_no" style="cursor: pointer;">Mobile</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="zone" style="cursor: pointer;">Zone</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="distributor_name" style="cursor: pointer;">Distributor Name</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="district" style="cursor: pointer;">District</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="code" style="cursor: pointer;">Code</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="import_code" style="cursor: pointer;">Import Code</th>
        </tr>
    </thead>
    <tbody>
    	@include('admin.ime.result_data')
    </tbody>
</table>
</div>
@endsection

                    