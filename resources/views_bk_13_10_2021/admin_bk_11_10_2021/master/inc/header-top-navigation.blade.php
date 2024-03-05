@php
$getNotification = allPendingNotification();
@endphp
<style>
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .header .header-container .nav-left .notifications .dropdown-menu, .header .header-container .nav-right .notifications .dropdown-menu {
            min-width: 400px !important;
            padding: 0;
        }
        .notifications-font {
            font-size: 1.5rem !important;
            color: #000 !important;
        }
        .fontsz-icon {
            font-size: 1.9rem !important;
        }
        .all-view-notification {
            padding: 20px !important;
            font-size: 1rem !important;
        }
        .header .header-container .nav-left>li>a i, .header .header-container .nav-right>li>a i {
            font-size: 1.5rem !important;
        }
        .cdlogoutbtn {
            width: 250px;
            height: auto;
        }
        .header .header-container .nav-right .dropdown-menu > li > a {
            line-height: 2.5;
            min-height: auto;
            padding: 24px 33px;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) {
        .header .header-container .nav-left .notifications .dropdown-menu, .header .header-container .nav-right .notifications .dropdown-menu {
            min-width: 400px !important;
            padding: 0;
        }
        .fontsz-icon {
            font-size: 1.9rem !important;
        }
        .header .header-container .nav-left>li>a i, .header .header-container .nav-right>li>a i {
            font-size: 1.5rem !important;
        }
        .cdlogoutbtn {
            width: 250px;
            height: auto;
        }
        .header .header-container .nav-right .dropdown-menu > li > a {
            line-height: 2.5;
            min-height: auto;
            padding: 24px 33px;
        }
    }
</style>
<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left">
            <li>
                <a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);">
                    <i class="fa fa-bars"></i></a>
            </li>
        </ul>
        <ul class="nav-right">
            <li class="notifications dropdown">
                <span class="counter bgc-red">{{ $getNotification['totalNotification'] }}</span> 
                <a href="#" class="dropdown-toggle no-after" data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="pX-20 pY-15 bdB">
                        <i class="fa fa-bell pR-10 fontsz-icon"></i> 
                        <span class="notifications-font fsz-sm fw-600 c-grey-900">Pending Notification</span>
                    </li>

                    <li>
                        <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm">
                            <li>
                                <a href="{{ url('/pending-order') }}" class="peers fxw-nw td-n p-10 bdB c-grey-800 cH-blue bgcH-grey-100">
                                    <div class="peer peer-greed">
                                        <span class="fontsz-icon d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">{{ $getNotification['pending_order'] }}</span>
                                        <span class="fw-500 notifications-font"> Pending Order</span> 
                                    </div>
                                    <span class="all-view-notification badge badge-pill fl-r badge-success lh-0 p-10 pull-right">View All</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/pending-message') }}" class="peers fxw-nw td-n p-10 bdB c-grey-800 cH-blue bgcH-grey-100">
                                    <div class="peer peer-greed">
                                        <span class="fontsz-icon d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">{{ $getNotification['pending_message'] }}</span>
                                        <span class="fw-500 notifications-font"> Pending Message</span> 
                                    </div>
                                    <span class="all-view-notification badge badge-pill fl-r badge-info lh-0 p-10 pull-right">View All</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/pending-leave') }}" class="peers fxw-nw td-n p-10 bdB c-grey-800 cH-blue bgcH-grey-100">
                                    <div class="peer peer-greed">
                                        <span class="fontsz-icon d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">{{ $getNotification['pending_leave'] }}</span>
                                        <span class="fw-500 notifications-font"> Pending Leave</span> 
                                    </div>
                                    <span class="all-view-notification badge badge-pill fl-r badge-info lh-0 p-10 pull-right">View All</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('imei.disputeList') }}" class="peers fxw-nw td-n p-10 bdB c-grey-800 cH-blue bgcH-grey-100">
                                    <div class="peer peer-greed">
                                        <span class="fontsz-icon d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">{{ $getNotification['dispute_imei'] }}</span>
                                        <span class="fw-500 notifications-font"> Dispute Message</span> 
                                    </div>
                                    <span class="all-view-notification badge badge-pill fl-r badge-danger lh-0 p-10 pull-right">View All</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                    <div class="peer mR-10"><img class="w-2r bdrs-50p" src="{{asset('public/admin/men/10.jpg')}}" alt="" /></div>
                    <div class="peer">
                        <span class="fsz-sm c-grey-900 fontsz-icon">
                            <i class="fa fa-caret-down fa-2x" style="padding: 5px"></i>Retail Gear
                        </span>
                    </div>
                </a>
                <ul class="dropdown-menu fsz-sm fontsz-icon cdlogoutbtn">
                    <li>
                        <a href="{{ url('getUserProfile/'.Auth::user()->id) }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="fa  fa-user mR-10"></i> <span>Profile</span>
                        </a>
                    </li> 
                    <li role="separator" class="divider"></li>
                    <li>
                        <a cclass="d-b td-n pY-5 bgcH-grey-100 c-grey-700" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"><i class="fa fa-retweet mR-10"></i> <span>{{ __('Logout') }}</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>