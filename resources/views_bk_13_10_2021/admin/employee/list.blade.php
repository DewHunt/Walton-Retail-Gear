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
@media (min-width: 768px) and (max-width: 1024px) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Employee List</h4>
<div class="col-md-12 mB-10">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddEmployeeModal">Add Employee</button>

            <input type="hidden" class="EmpPassword" readonly="">
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
                <th class="sorting" data-sorting_type="asc" data-column_name="name" style="cursor: pointer;">Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="designation" style="cursor: pointer;">Designation</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="department" style="cursor: pointer;">Department</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="mobile_number" style="cursor: pointer;">Mobile</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.employee.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>
<!--Add New Employee Modal Start -->
<div class="modal fade" id="AddEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddEmployee" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row" id="ApiSearchDiv">
                        <div class="form-group col-md-10">
                            <input type="text" class="form-control" id="search_employee_id" placeholder="Employee Search">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-primary btn-block" id="search_employee_button">Search</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Employee ID</label>
                                <input type="text" name="employee_id" class="form-control ApiId" placeholder="Enter Employee ID"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control ApiName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="name-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control ApiDesignation" placeholder="Designation"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Mobile Number <span class="required">*</span></label>
                                <input type="text" name="mobile_number" class="form-control ApiMobileNumber Number" placeholder="Mobile Number" maxlength="11"  minlength="11" required="" />
                                <span class="text-danger">
                                    <strong id="phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Email <span class="required">*</span></label>
                                <input type="text" name="email" class="form-control ApiEmail" placeholder="Email Address" required="required"/>
                                <span class="text-danger">
                                    <strong id="email-error"></strong>
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Operating Unit</label>
                                <input type="text" name="operating_unit" class="form-control ApiOperatingUnit" placeholder="Operating Unit" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Product</label>
                                <input type="text" name="product" class="form-control ApiProduct" placeholder="Product Name" />
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Department</label>
                                <input type="text" name="department" class="form-control ApiDepartment" placeholder="Department"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Section</label>
                                <input type="text" name="section" class="form-control ApiSection" placeholder="Section"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Sub Section</label>
                                <input type="text" name="sub_section" class="form-control ApiSubSection" placeholder="Sub Section"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Employee Modal End -->


<!--Edit & Update Modal Start -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Information</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateEmployee" enctype="multipart/form-data">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Employee ID</label>
                                <input type="text" name="employee_id" class="form-control UpdateApiId" placeholder="Enter Employee ID" readonly=""/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control UpdateApiName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="update-name-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control UpdateApiDesignation" placeholder="Designation"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Mobile Number <span class="required">*</span></label>
                                <input type="text" name="mobile_number" class="form-control UpdateApiMobileNumber Number" maxlength="11"  minlength="11" placeholder="Mobile Number" required="" />
                                <span class="text-danger">
                                    <strong id="update-phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Email <span class="required">*</span></label>
                                <input type="text" name="email" class="form-control UpdateApiEmail" placeholder="Email Address" required="required"/>
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Operating Unit</label>
                                <input type="text" name="operating_unit" class="form-control UpdateApiOperatingUnit" placeholder="Operating Unit"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Product</label>
                                <input type="text" name="product" class="form-control UpdateApiProduct" placeholder="Product Name"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Department</label>
                                <input type="text" name="department" class="form-control UpdateApiDepartment" placeholder="Department"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Section</label>
                                <input type="text" name="section" class="form-control UpdateApiSection" placeholder="Section"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Sub Section</label>
                                <input type="text" name="sub_section" class="form-control UpdateApiSubSection" placeholder="Sub Section"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="radio" id="option1" name="status" class="UpdateApiStatus" value="1"> Active
                                </label>  &nbsp;&nbsp; 
                                <label>
                                    <input type="radio" id="option2" name="status" class="UpdateApiStatus" value="0"> In-Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection