@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">User's Menu Permission</h4>
<div class="col-md-12 mB-10">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddUserModal" style="margin-left: 5px">Add New User</button>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="">
        <div class="masonry-item">
            <form method="POST" action="{{ route('user.menu_permission_save') }}">
                @csrf
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900" style="margin-left: 20px;"> 
                        <input class="form-check-input_mobile mobileCheckBox" type="checkbox" id="select-all"> Select All
                    </h6>
                </div>
                @if(isset($parentMenus))
                @foreach($parentMenus as $row)
                @php $getPermissionMenuId = explode(",",$userInfo['permission_menu_id']); @endphp
                <div class="bgc-white p-20 bd" style="border: 1px solid #03a9f4 !important;">
                    <h6 class="c-grey-900" style="margin-left: 20px;"> 
                        <input class="form-check-input_mobile checkParentMenuId mobileCheckBox" data-id="{{ $row->id }}" type="checkbox" name="permission_menu_id[]" id="{{ $row->id }}" value="{{ $row->id }}" @if(in_array($row->id,$getPermissionMenuId)) checked="checked" @endif> {{ $row->menu_name }}
                    </h6>
                    <div class="mb-3 row">
                        @foreach($childMenus as $chMenu)
                        @if($chMenu->parent_menu == $row->id)
                        <div class="col-sm-3" style="padding-right: 0px !important;">
                            <div class="form-check">
                                <label class="form-label form-check-label">
                                    <input class="form-check-input_mobile mobileCheckBox childParentId_{{ $row->id }}" type="checkbox" name="permission_menu_id[]" id="{{ $chMenu->id }}" value="{{ $chMenu->id }}" @if(in_array($chMenu->id,$getPermissionMenuId)) checked="checked" @endif> {{ $chMenu->menu_name }}
                                </label>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
                <input type="hidden" name="user_id" value="{{ $userInfo['id'] }}">
                <div class="bgc-white p-20 bd">
                    <div class="mb-1 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
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
                    <div class="form-group" id="error_field">
                        <label>User Name <span class="required">*</span></label>
                        <input id="name" type="text" class="form-control" name="name" placeholder="Enter User Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }} <span class="required">*</span></label>

                        <input id="email" type="email" class="form-control" name="email" placeholder="Enter User Email" value="{{ old('email') }}" required autocomplete="email">
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
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> <button type="submit" class="btn btn-primary">{{ __('Register') }}</button></div>
            </form>
        </div>
    </div>
</div>
<!--Add New User Modal End -->
@endsection