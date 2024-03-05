@extends('layouts.app')

@section('content')
<style type="text/css">
/* 
  ##Device = Desktops
  ##Screen = 1281px to higher resolution desktops
*/

@media (min-width: 1281px) {
  /*.example {background: blue;}*/
  .logowh {
    	width: 120px; 
    	height: 120px;
    	background-color: #fff !important;
    	border-radius: 50% !important;
    	position: relative !important;
    }
}

/* 
  ##Device = Laptops, Desktops
  ##Screen = B/w 1025px to 1280px
*/

@media (min-width: 1025px) and (max-width: 1280px) {
  /*.example {background: pink;}*/
  .logowh {
    	width: 120px; 
    	height: 120px;
    	background-color: #fff !important;
    	border-radius: 50% !important;
    	position: relative !important;
    }
}

/* 
  ##Device = Tablets, Ipads (portrait)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) {
  /*.example {background: orange;}*/
  .minMobWidth {
    	background:url('public/admin/static/images/bg.jpg') no-repeat;
    	background-size: cover;
		ms-flex: 0 0 100% !important;
		flex: 0 0 100% !important;
		max-width: 100% !important;
		height: 1200px;
	}
	.middle-position {
		text-align: center;
		margin: 0 auto;
		padding-top: 450px;
		color: #ffffff;
		padding-right: 10px;
	}
	.c-grey-900, .cH-grey-900:hover {
	    color: #fff !important;
	    font-size: 30px;
	}
	.text-dark {
	    color: #fff !important;
	    font-size: 36px;
	}
	a {
		color: #ffffff;
	}
	a:focus, a:hover {
	    text-decoration: none;
	    color: #ffffff;
	}
	.form-control {
    	padding: 1.375rem .75rem;
    	font-size: 1.875rem;
    }
    .btn {
    	font-size: 1.875rem;
    }
    .log-forget-text {
    	font-size: 36px;
    }
    .custom-peer-greed-text {
    	font-size: 36px;
    }
    .logowh {
    	width: 120px; 
    	height: 120px;
    	background-color: #fff !important;
    	border-radius: 50% !important;
    	position: relative !important;
    }
    .clogbtn {
    	font-size: 2.75rem;
		width: 300px;
    }
}

/* 
  ##Device = Tablets, Ipads (landscape)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  /*.example {background: yellow;}*/
}

/* 
  ##Device = Low Resolution Tablets, Mobiles (Landscape)
  ##Screen = B/w 481px to 767px
*/

@media (min-width: 481px) and (max-width: 767px) {
  /*.example {background: red;}*/
  .minMobWidth {
    	background:url('public/admin/static/images/bg.jpg') no-repeat;
    	background-size: cover;
		ms-flex: 0 0 100% !important;
		flex: 0 0 100% !important;
		max-width: 100% !important;
		height: 1200px;
	}
	.middle-position {
		text-align: center;
		margin: 0 auto;
		padding-top: 450px;
		color: #ffffff;
		padding-right: 10px;
	}
	.c-grey-900, .cH-grey-900:hover {
	    color: #fff !important;
	    font-size: 30px;
	}
	.text-dark {
	    color: #fff !important;
	    font-size: 36px;
	}
	a {
		color: #ffffff;
	}
	a:focus, a:hover {
	    text-decoration: none;
	    color: #ffffff;
	}
	.form-control {
    	padding: 1.375rem .75rem;
    	font-size: 1.875rem;
    }
    .btn {
    	font-size: 1.875rem;
    }
    .log-forget-text {
    	font-size: 20px;
    }
    .custom-peer-greed-text {
    	font-size: 20px;
    }
    .logowh {
    	width: 120px; 
    	height: 120px;
    	background-color: #fff !important;
    	border-radius: 50% !important;
    	position: relative !important;
    }
    .clogbtn {
    	font-size: 1.75rem;
		width: 200px;
    }
  
}

/* 
  ##Device = Most of the Smartphones Mobiles (Portrait)
  ##Screen = B/w 320px to 479px
*/

@media (min-width: 320px) and (max-width: 480px) {
  /*.example {background: green;}*/
  .minMobWidth {
    	background:url('public/admin/static/images/bg.jpg') no-repeat;
    	background-size: cover;
		ms-flex: 0 0 100% !important;
		flex: 0 0 100% !important;
		max-width: 100% !important;
		height: 1200px;
	}
	.middle-position {
		text-align: center;
		margin: 0 auto;
		padding-top: 450px;
		color: #ffffff;
		padding-right: 10px;
	}
	.c-grey-900, .cH-grey-900:hover {
	    color: #fff !important;
	    font-size: 30px;
	}
	.text-dark {
	    color: #fff !important;
	    font-size: 14px;
	}
	a {
		color: #ffffff;
	}
	a:focus, a:hover {
	    text-decoration: none;
	    color: #ffffff;
	}
	.form-control {
    	padding: 0px !important;
    	font-size: 0.75rem;
    }
    .btn {
    	font-size: 1.875rem;
    }
    .log-forget-text {
    	font-size: 20px;
    }
    .custom-peer-greed-text {
    	font-size: 20px;
    }
    .logowh {
    	width: 120px; 
    	height: 120px;
    	background-color: #fff !important;
    	border-radius: 50% !important;
    	position: relative !important;
    }
    .clogbtn {
        font-size: 0.75rem;
        width: 85px;
    }
    .checkbox label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 0px;
        margin-bottom: 0;
    }
}
</style>
<div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv d-md-none d-lg-block" style="background-image: url({{asset('public/admin/static/images/bg.jpg')}});">
	<div class="pos-a centerXY">
		<div class="bgc-white bdrs-50p pos-r logowh">
			<img class="pos-a centerXY" src="{{asset('public/admin/static/images/logo.png')}}" alt="" width="70" height="60"/>
		</div>
	</div>
</div>
<div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r minMobWidth">
	<!--<h6 class="example">Device Controll</h6>-->
	<div class="col-md-12 middle-position">
		<h4 class="fw-300 c-grey-900 mB-40">{{ __('Login') }}</h4>
		<form method="POST" action="{{ route('login') }}">
			{{ csrf_field() }}
			<div class="form-group">
				<label class="text-normal text-dark">{{ __('E-Mail Address') }} || Employee ID</label>
				<input id="login" type="text" class="form-control {{ $errors->has('employee_id') || $errors->has('email') ? 'is-invalid' : '' }}" name="login" value="{{ old('employee_id') ? : old('email') }}" required autocomplete="email" autofocus placeholder="name@sitename.com | ex:12345">
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
							    <span class="peer peer-greed custom-peer-greed-text">{{ __('Remember Me') }}</span>
							</label>
						</div>
						@if (Route::has('password.request'))
						<p class="log-forget-text"> <i class="fa fa-key" aria-hidden="true"></i>
							<a href="{{ route('password.request') }}">Forgot password?</a>
						</p>
						@endif
					</div>
					<div class="peer"><button class="btn btn-primary clogbtn">{{ __('Login') }}</button></div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
