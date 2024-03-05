@extends('admin.master.master')
@section('content')
<style>
.cp {
    padding:5px
}
.csearch {
    width:285px;
} 
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 320px) 
and (max-device-width: 568px)
and (-webkit-min-device-pixel-ratio: 2) {
    .modal-body select.form-control:not([size]):not([multiple]) {
        height: calc(3.0625rem + 2px) !important;
    }
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3) { 
    .modal-body select.form-control:not([size]):not([multiple]) {
        height: calc(3.0625rem + 2px) !important;
    }
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .modal-body select.form-control:not([size]):not([multiple]) {
        height: calc(3.0625rem + 2px) !important;
    }
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
</style>

<h4 class="c-grey-900 mB-20">Menu List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddMenuModal" style="margin-left: 5px">Add Menu</button>
        </div>
    </div>
</div>

<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="menu_name" style="cursor: pointer;">Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="parent_menu" style="cursor: pointer;">Parent</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="menu_link" style="cursor: pointer;">Link</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.menu.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id"/>
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc"/>
</div>

<!--Add New Menu Modal Start -->
<div class="modal fade" id="AddMenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Menu</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Field Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddMenu">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Parent Menu</label>
                                <select class="form-control" style="width: 100%;" name="parentMenuId">
                                    <option value="">Select Parent Menu</option>
                                    @if(isset($menuList))
                                    @foreach ($menuList as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Menu Name <span class="required">*</span></label>
                                <input type="text" name="menuName" class="form-control" required=""/>
                                <span class="text-danger">
                                    <strong id="menu-name-error"></strong>
                                </span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Menu Link</label>
                                <input type="text" name="menuLink" class="form-control"/>
                                <span class="text-danger">
                                    <strong id="menu-link-error"></strong>
                                </span>
                            </div>

                            <div class="col-md-6 mb-2" style="display:none">
                                <label>Order By</label>
                                <input type="number" name="orderBy" class="form-control" min="1"/>
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
<!--Add New Menu Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editmenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Menu</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Field Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateMenu">
                <input type="hidden" name="update_id" id="update_id"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Parent Menu</label>
                            <select class="form-control" style="width: 100%;" name="parentMenuId">
                                <option value="">Select Parent Menu</option>
                                @if(isset($menuList))
                                @foreach ($menuList as $menu)
                                <option value="{{ $menu->id }}" class="parentMenuId{{ $menu->id }}">{{ $menu->menu_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Menu Name <span class="required">*</span></label>
                            <input type="text" name="menuName" class="form-control menuName" required=""/>
                            <span class="text-danger">
                                <strong id="menu-name-error"></strong>
                            </span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Menu Link <span class="required">*</span></label>
                            <input type="text" name="menuLink" class="form-control menuLink" required=""/>
                            <span class="text-danger">
                                <strong id="menu-link-error"></strong>
                            </span>
                        </div>

                        <div class="col-md-6 mb-2" style="display:none">
                            <label>Order By</label>
                            <input type="number" name="orderBy" class="form-control orderBy" min="1"/>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                            <label>
                                <input type="radio" id="option1" name="status" value="1"> Active
                            </label>  &nbsp;&nbsp; 
                            <label>
                                <input type="radio" id="option2" name="status" value="0"> In-Active
                            </label>
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
<!--Edit & Update Modal End -->
@endsection