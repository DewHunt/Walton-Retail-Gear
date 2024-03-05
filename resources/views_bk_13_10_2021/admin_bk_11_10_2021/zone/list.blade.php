@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Zone List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddZoneModal" style="margin-left: 5px">Add Zone</button>

            <button  style="display: none" type="button" class="btn btn-info pull-right btn-sm" onclick="AddAllZoneByApi()">Add to Zone By Api</button>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" name="serach" id="serach" class="form-control" />
            </div>
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="zone_name" style="cursor: pointer;">Zone Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.zone.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Add New Zone Modal Start -->
<div class="modal fade" id="AddZoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Zone</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddZone">
                @csrf
                <div class="modal-body">
                    <div class="form-row" id="ApiSearchDiv">
                        <div class="form-group col-md-10">
                            <input type="text" class="form-control" id="search_zone_id" placeholder=" Search Zone">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-primary" id="search_zone_button">Search</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Zone Name <span class="required">*</span></label>
                        <input type="text" name="zone_name" class="form-control ApiZoneName" placeholder="Zone Name"required=""/>
                        <span class="text-danger">
                            <strong id="name-error"></strong>
                        </span>
                        <input type="hidden" name="zone_id" class="form-control ApiZoneId">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="col-sm-5">
                            <div>
                                <label>
                                    <input type="radio" name="status" checked="checked" value="1"> Active
                                </label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Zone Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editZoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Zone</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateZone">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Zone Name <span class="required">*</span></label>
                        <input type="text" name="zone_name" class="form-control UpdateApiZoneName" placeholder="Zone Name" required=""/>
                        <span class="text-danger">
                            <strong id="update-name-error"></strong>
                        </span>
                        <input type="hidden" name="zone_id" class="form-control UpdateApiZoneId">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="col-sm-5">
                            <div>
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