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
	<h4 class="fw-300 c-grey-900 mB-40">{{ __('Login') }}</h4>
	<form method="POST" action="{{ route('login') }}">
    @csrf
		<div class="form-group">
			<label class="text-normal text-dark">{{ __('E-Mail Address') }} || Employee ID</label>
			{{-- <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus/>
			@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror --}}

			<input id="login" type="text" class="form-control {{ $errors->has('employee_id') || $errors->has('email') ? 'is-invalid' : '' }}" name="login" value="{{ old('employee_id') ? : old('email') }}" required autocomplete="email" autofocus placeholder="name@sitename.com | ex:37992">
            @if ($errors->has('employee_id') || $errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('employee_id') ? : $errors->first('email') }}</strong>
                </span>
            @endif
								
		</div>
		<div class="form-group">
			<label class="text-normal text-dark">{{ __('Password') }}</label>
			<input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password"/>
			@error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<div class="peers ai-c jc-sb fxw-nw">
				<div class="peer">
					<div class="checkbox checkbox-circle checkbox-info peers ai-c">
						<input class="peer" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
						<label class="peers peer-greed js-sb ai-c">
						    <span class="peer peer-greed">{{ __('Remember Me') }}</span>
						</label>
					</div>
					@if (Route::has('password.request'))
					<p> <i class="fa fa-key" aria-hidden="true"></i>
						<a href="{{ route('password.request') }}">Forgot password?</a>
					</p>
					@endif
				</div>
				<div class="peer"><button class="btn btn-primary">{{ __('Login') }}</button></div>
			</div>
		</div>
	</form>
</div>
@endsection
