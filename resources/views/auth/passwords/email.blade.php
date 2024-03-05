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
	<h4 class="fw-300 c-grey-900 mB-40">{{ __('Reset Password') }}</h4>
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif		
	<form method="POST" action="{{ route('password.email') }}">
    @csrf
		<div class="form-group">
			<label class="text-normal text-dark">{{ __('E-Mail Address') }}</label>
			<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror	
		</div>
		<div class="form-group">
			<div class="peers ai-c jc-sb fxw-nw">
				<div class="peer"><button class="btn btn-primary">{{ __('Send Password Reset Link') }}</button></div>
			</div>
		</div>
	</form>
</div>
@endsection
