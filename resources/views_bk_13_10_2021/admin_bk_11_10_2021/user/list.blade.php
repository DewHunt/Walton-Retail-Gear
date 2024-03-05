@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">User List</h4>
<div class="col-md-12 mB-10">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddUserModal" style="margin-left: 5px">Add New User</button>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" name="serach" id="serach" class="form-control"/>
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
                <th class="sorting" data-sorting_type="asc" data-column_name="employee_id" style="cursor: pointer;">Employee ID</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer;">Email</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.user.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Add New User Modal Start -->
<div class="modal fade" id="AddUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddUser">
                @csrf
                <div class="modal-body">
                    <div class="form-group select-h">
                        <label>Select Employee<span class="required">*</span></label>
                        <select class="form-control select2 empId" data-placeholder="Select" style="width: 100%;" name="employee_id" required="">
                            <option value="">Select Employee</option>
                            @if(isset($empList) && !empty($empList))
                            @foreach($empList as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group" id="error_field">
                        <label>User Name</label>
                        <input id="name" type="text" class="form-control uname" name="name" placeholder="Enter User Name" value="{{ old('name') }}" autocomplete="name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }} <span class="required">*</span></label>

                        <input id="email" type="email" class="form-control uemail" name="email" placeholder="Enter User Email" value="{{ old('email') }}" required autocomplete="email">
                        <span class="text-danger">
                            <strong id="user-email-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('Password') }} <span class="required">*</span></label>

                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter User Password Minimum 5 Digit" required autocomplete="new-password">

                        <span class="text-danger">
                            <strong id="user-password-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }} <span class="required">*</span></label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                        <span class="text-danger">
                            <strong id="user-confirm-password-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                        <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                        <label><input type="radio" name="status" value="0"> In-Active</label>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> <button type="submit" class="btn btn-primary">{{ __('Register') }}</button></div>
            </form>
        </div>
    </div>
</div>
<!--Add New User Modal End -->


<!--Edit & Update Modal Start -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update User Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateUser">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="form-group select-h">
                        <label>Select Employee<span class="required">*</span></label>
                        <select class="form-control empId" data-placeholder="Select" style="width: 100%;" name="employee_id" required="">
                            <option value="">Select Employee</option>
                            @if(isset($empList) && !empty($empList))
                            @foreach($empList as $row)
                            <option value="{{ $row->id }}" class="vEmpId{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group" id="error_field">
                        <label>User Name</label>
                        <input id="name" type="text" class="form-control userName" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }} <span class="required">*</span></label>
                        <input id="email" type="email" class="form-control userEmail" name="email" value="{{ old('email') }}" required autocomplete="email">

                        <span class="text-danger">
                            <strong id="uuser-email-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control userPassword" name="password" autocomplete="new-password">

                        <span class="text-danger">
                            <strong id="uuser-password-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">

                        <span class="text-danger">
                            <strong id="uuser-confirm-password-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                        <label><input type="radio" id="option1" name="status" class="UpdateUserStatus" value="1"> Active</label>  &nbsp;&nbsp; 
                        <label><input type="radio" id="option2" name="status" class="UpdateUserStatus" value="0"> In-Active</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update_employee_id" id="update_employee_id">
                    <input type="hidden" name="old_password" id="old_password">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->
@endsection