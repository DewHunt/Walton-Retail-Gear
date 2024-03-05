@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">User Profile</h4>
<div class="col-md-12 mB-20">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddUserModal" style="margin-left: 5px">Add New User</button>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="row">
        <div class="masonry-item col-md-6 masonry-col">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900">Profile Update</h6>
                <div class="mT-30">
                    <form method="post" action="{{ url('userProfileUpdate') }}">
                        @csrf
                        <input type="hidden" name="update_id" value="{{ $UserProfileInfo->id }}">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="{{ $UserProfileInfo->name }}" readonly=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" value="{{ $UserProfileInfo->email }}" readonly=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" />
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password_confirmation"/>
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary pull-right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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