@extends('layouts.app')

@section('content')
<div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image: url({{asset('public/admin/static/images/bg.jpg')}});">
    <div class="pos-a centerXY">
        <div class="bgc-white bdrs-50p pos-r" style="width: 120px; height: 120px;">
            <img class="pos-a centerXY" src="{{asset('public/admin/static/images/logo.png')}}" alt="" />
        </div>
    </div>
</div>
<div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width: 320px;">
    <h4 class="fw-300 c-grey-900 mB-40">{{ __('Account Activated') }}</h4>
    <form action="{{ route('employee.account_update') }}" method="post">
    @csrf
        <div class="form-group">
            <label class="text-normal text-dark">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password"/>
            <span class="text-danger">{{ $errors->first('password') }}</span>
        </div>
        <div class="form-group">
            <label class="text-normal text-dark">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password"/>
            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
        </div>
        
        <div class="form-group">
            <div class="peers ai-c jc-sb fxw-nw">
                <input type="hidden" name="activation_key" value="@if(isset($key)) {{ $key }} @else {{--  --}} @endif" readonly="">
                <div class="peer"><button class="btn btn-primary">{{ __('Register') }}</button></div>
            </div>
        </div>
    </form>
</div>
@endsection
