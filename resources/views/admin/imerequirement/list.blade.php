@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">IMEI Requirement List</h4>
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
            <th class="sorting" data-sorting_type="asc" data-column_name="imei_number" style="cursor: pointer;">IMEI</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="description" style="cursor: pointer;">Description</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="date" style="cursor: pointer;">Date</th>
            {{-- <th style="width:5px">Action </th> --}}
        </tr>
    </thead>
    <tbody>
    	@include('admin.imerequirement.result_data')
    </tbody>
</table>
</div>

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editIMEIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update IMEI Requirement</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateIMEIRequirement">
            <input type="hidden" name="update_id" id="update_id"/>
            <input type="hidden" name="_method" value="PUT"/>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>IMEI Number <span class="required">*</span></label>
                        <input type="text" maxlength="15"  minlength="15" name="imei_number" class="form-control imei_number" required=""/>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Description</label>
                        <textarea class="form-control description" name="description"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="update_id" class="update_id"/>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection

                    