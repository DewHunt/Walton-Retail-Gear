@extends('admin.master.master')
@section('content')
<style>
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 320px) 
and (max-device-width: 568px)
and (-webkit-min-device-pixel-ratio: 2) {
    .cebtn {
        width: 200px;
        text-align: center;
    }
}

@media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
    .cebtn {
        width: 200px;
        text-align: center;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Banner List</h4>
<div class="col-md-12 mB-10">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddBannerModal" style="margin-left: 5px">Add Banner</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-9"></div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Banner</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($bannerList))
            @foreach($bannerList as $row)
            <tr>
                <td>{{ ++$loop->index }}.</td>
                <td>
                    @if(isset($row->image_path) && !empty($row->image_path) && $row->image_path !=null)
                    <img src="{{ $row->image_path }}" alt="" width="380" height="150"/>
                    @endif
                </td>
                <td>
                    <input data-id="{{ $row->id }}" class="banner-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
                </td>
                <td>
                    <form action="{{ route('banner.destroy',$row->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" data-id="{{ $row->id }}" id="editBannerInfo" class="btn btn-primary btn-sm cebtn" data-toggle="modal" data-target="#editBannerModal">Edit</button>

                        <button onclick="return confirm('Are you sure to delete?')" type="submit" class="btn btn-danger btn-sm cebtn">
                            Delete</i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>



<!--Add Modal Start -->
<div class="modal fade" id="AddBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Banner</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddBanner" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label>Group <span class="required">*</span></label>
                                <select class="form-control"  style="width: 100%;" name="banner_for" required="">
                                    <option value="">Select</option>
                                    <option value="all" selected="selected">All</option>
                                    <option value="bp">BP</option>
                                    <option value="retailer">Retailer</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Banner Pic <span class="required">*</span></label><br/>
                                <span class="text-danger banner-error"></span>
                                <input type="file" name="banner_pic" class="form-control" required=""/>
                                <p>Banner Size Should Be: 380px x 150px</p>
                            </div>

                            <div class="col-md-3" style="margin-top:10px">
                                <label>Status <span class="required">*</span></label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <br/>
                                <label>
                                    <input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                                <span class="text-danger status-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add Modal End -->


<!--Edit & Update Modal Start -->
<div class="modal fade" id="editBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Banner</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateBanner" enctype="multipart/form-data">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label>Group<span class="required">*</span></label>
                                <select class="form-control" style="width: 100%;" name="banner_for" required="">
                                    <option value="">Select</option>
                                    <option value="all" class="uall">All</option>
                                    <option value="bp" class="ubp">BP</option>
                                    <option value="retailer" class="uretailer">Retailer</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Banner Pic <span class="required">*</span></label><br/>
                                <span class="text-danger banner-error"></span>
                                <input type="file" name="banner_pic" class="form-control"/>
                                <p>Banner Size Should Be: 380px x 150px</p>
                                <br/>
                                <span id="img-tag"></span>
                            </div>

                            <div class="col-md-3" style="margin-top:10px">
                                <label>Status <span class="required">*</span></label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <br/>
                                <label>
                                    <input type="radio" id="option1" name="status" value="1"> Active
                                </label>  &nbsp;&nbsp; 
                                <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                                <span class="text-danger status-error"></span>
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