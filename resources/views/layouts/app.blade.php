<!DOCTYPE html>
<html>
    <head>
        <title>Sign In</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <style>
            #loader {
                transition: all 0.3s ease-in-out;
                opacity: 1;
                visibility: visible;
                position: fixed;
                height: 100vh;
                width: 100%;
                background: #fff;
                z-index: 90000;
            }
            #loader.fadeOut {
                opacity: 0;
                visibility: hidden;
            }
            .spinner {
                width: 40px;
                height: 40px;
                position: absolute;
                top: calc(50% - 20px);
                left: calc(50% - 20px);
                background-color: #333;
                border-radius: 100%;
                -webkit-animation: sk-scaleout 1s infinite ease-in-out;
                animation: sk-scaleout 1s infinite ease-in-out;
            }
            @-webkit-keyframes sk-scaleout {
                0% {
                    -webkit-transform: scale(0);
                }
                100% {
                    -webkit-transform: scale(1);
                    opacity: 0;
                }
            }
            @keyframes sk-scaleout {
                0% {
                    -webkit-transform: scale(0);
                    transform: scale(0);
                }
                100% {
                    -webkit-transform: scale(1);
                    transform: scale(1);
                    opacity: 0;
                }
            }
        </style>
		<link href="{{asset('public/admin/css/style.css')}}" rel="stylesheet" />
    </head>
    <body class="app">
        <div id="loader"><div class="spinner"></div></div>
        <script type="text/javascript">
            window.addEventListener("load", () => {
                const loader = document.getElementById("loader");
                setTimeout(() => {
                    loader.classList.add("fadeOut");
                }, 300);
            });
        </script>
        <div class="peers ai-s fxw-nw h-100vh">
			@yield('content')
        </div>
		<script type="text/javascript" src="{{asset('public/admin/js/vendor.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/bundle.js')}}"></script>
        <script>
        jQuery(document).ready(function(){
            @if($message = Session::get('success'))
                Notiflix.Notify.Success( '{{ $message }}' );
            $('.active_list').addClass("active");
            $('.active_form').removeClass('active');
            @elseif($message = Session::get('error'))
                Notiflix.Notify.Failure( '{{ $message }}' );
            $('.active_form').addClass("active");
            $('.active_list').removeClass('active');
            @elseif($message = Session::get('warning'))
                Notiflix.Notify.Warning('{{ $message }}' );
            @elseif($message = Session::get('info'))
                Notiflix.Notify.Info( '{{ $message }}' );
            //@else
                //Notiflix.Notify.Failure( '{{ $message }}' );              
            @endif
        });
        </script>
    </body>
</html>
