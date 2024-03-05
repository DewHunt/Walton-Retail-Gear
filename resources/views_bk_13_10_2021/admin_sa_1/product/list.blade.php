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
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3){
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Product List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddProductModal" style="margin-left:5px;">Add Product</button>  

            <button style="display: none" type="button" class="btn btn-info pull-right btn-sm" onclick="AddAllProductByApi()">Add to Product By Api</button>
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
                <th class="sorting" data-sorting_type="asc" data-column_name="product_master_id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="product_model" style="cursor: pointer;">Model</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="mrp_price" style="cursor: pointer;">MRP</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="msdp_price" style="cursor: pointer;">MSDP</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="msrp_price" style="cursor: pointer;">MSRP</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="product_type" style="cursor: pointer;">Type</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="category2" style="cursor: pointer;">Category</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th style="width:15%">Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.product.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="product_master_id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
</div>

<!--Add New Product Modal Start -->
<div class="modal fade" id="AddProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="{{route('product.add')}}" id="AddProduct">
                @csrf
                <div class="modal-body">
                    <div class="form-row" id="ApiSearchDiv">
                        <div class="form-group col-md-10">
                            <input type="text" class="form-control" id="search_product_id" placeholder="Search By Model / Product Code">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-primary btn-block" id="search_product_button">Search</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Product Model <span class="required">*</span></label>
                            <input type="text" name="product_model" class="form-control ApiproductModel" placeholder="Model Number" required=""/>
                            <span class="text-danger">
                                <strong id="model-number-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Product Code</label>
                            <input type="text" name="product_code" class="form-control ApiproductCode"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Product Type</label>
                            <input type="text" name="product_type" class="form-control ApiproductType" placeholder="Product Type Ex:Cell Phone"/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>MRP Price <span class="required">*</span></label>
                            <input type="text" name="mrp_price" class="form-control ApiproductPrice" placeholder="MRP Price" required=""/>
                            <span class="text-danger">
                                <strong id="mrp-error"></strong>
                            </span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>MSDP Price <span class="required">*</span></label>
                            <input type="text" name="msdp_price" class="form-control ApiproductMsdp" placeholder="MSDP Price" required=""/>
                            <span class="text-danger">
                                <strong id="msdp-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>MSRP Price <span class="required">*</span></label>
                            <input type="text" name="msrp_price" class="form-control ApiproductMsrp" placeholder="MSRP Price" required=""/>
                            <span class="text-danger">
                                <strong id="msrp-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control ApiproductCategory" placeholder="Category Name"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="product_id" class="ApiproductId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Product Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editProductModal" tabindex="-2" role="dialog" aria-labelledby="editProductModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModal">Update Information</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateProduct" >
                <input type="hidden" name="product_master_id" id="product_master_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Product Model <span class="required">*</span></label>
                            <input type="text" name="product_model" class="form-control productModel" placeholder="Product Model" required=""/>
                            <span class="text-danger">
                                <strong id="update-model-number-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Product Code</label>
                            <input type="text" name="product_code" class="form-control productCode"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Product Type</label>
                            <input type="text" name="product_type" class="form-control productType" placeholder="Product Type"/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Price <span class="required">*</span></label>
                            <input type="text" name="mrp_price" class="form-control productPrice" placeholder="MRP Price" required=""/>
                            <span class="text-danger">
                                <strong id="update-mrp-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>MSDP Price <span class="required">*</span></label>
                            <input type="text" name="msdp_price" class="form-control productMsdp" placeholder="MSDP Price" required=""/>
                            <span class="text-danger">
                                <strong id="update-msdp-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>MSRP Price <span class="required">*</span></label>
                            <input type="text" name="msrp_price" class="form-control productMsrp" placeholder="MSRP Price" required=""/>
                            <span class="text-danger">
                                <strong id="update-msrp-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control productCategory" placeholder="Category Name"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="product_id" class="productId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->


<!--Stock Maintaince Modal Start -->
<div class="modal fade" id="productStockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Stock Maintaince</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="saveProductStockMaintain">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Default<span class="required">*</span></label>
                            <input type="number" class="form-control" name="default_qty" id="default_qty" min="1" required=""/>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Yeallow<span class="required">*</span></label>
                            <input type="number" class="form-control" name="yeallow_qty" id="yeallow_qty" min="1" required=""/>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Red<span class="required">*</span></label>
                            <input type="number" class="form-control" name="red_qty" id="red_qty" min="0" value="0"required=""/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="product_id" id="productId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Stock Maintaince Modal End -->
@endsection

