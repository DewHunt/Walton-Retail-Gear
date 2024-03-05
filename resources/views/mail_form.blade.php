@extends('admin.master.master')
@section('content')
<div class="masonry-item col-md-12">
    <div class="bgc-white p-20 bd">
        <h6 class="c-grey-900">
            Test Mail With Attachment
        </h6>
        <div class="mT-30">
            <form method="POST" action="{{ url('MailSendFrom') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                <label class="col-sm-2 col-form-label">Name <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required=""/>
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>
            <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                <label class="col-sm-2 col-form-label">Email <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required=""/>
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>
            </div>
            <div class="form-group row {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label class="col-sm-2 col-form-label">Phone <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter Phone" required=""/>
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Attachment </label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" id="a_file" name="a_file"/>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection