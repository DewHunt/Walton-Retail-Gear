@extends('layouts.app')

@section('content')
<div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image: url({{asset('public/admin/static/images/bg.jpg')}});">
	<div class="pos-a centerXY">
		<div class="bgc-white bdrs-50p pos-r" style="width: 120px; height: 120px;">
			<img class="pos-a centerXY" src="{{asset('public/admin/static/images/logo.png')}}" alt="" width="70" height="60"/>
		</div>
	</div>
</div>

<div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width: 320px;">
	<h4 class="fw-300 c-grey-900 mB-40">{{ __('Verify Your Email Address') }}</h4>
	@if (session('resent'))
		<div class="alert alert-success" role="alert">
			{{ __('A fresh verification link has been sent to your email address.') }}
		</div>
	@endif
	{{ __('Before proceeding, please check your email for a verification link.') }}
	{{ __('If you did not receive the email') }},
	<form method="POST" action="{{ route('verification.resend') }}">
    @csrf
		<div class="form-group">
			<div class="peers ai-c jc-sb fxw-nw">
				<button class="btn btn-primary">{{ __('click here to request another') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
