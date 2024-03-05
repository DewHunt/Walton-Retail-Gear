 <div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="{{ route('home') }}" class="td-n">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer">
                                <div class="logo">
                                    <img src="{{asset('public/admin/static/images/logo.png')}}" alt="WRG" width="70" height="50"/></div>
                            </div>
                            <!--
                            <div class="peer peer-greed">
                                <h5 class="lh-1 mB-0 logo-text" style="margin-top: 30px;">Retail Gear</h5>
                            </div>
                            -->
                        </div>
                    </a>
                </div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle">
                        <a href="#" class="td-n"><i class="ti-arrow-circle-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item mT-5 active">
                <a class="sidebar-link" href="{{route('home')}}" default>
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-home"></i></span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="sidebar-link" href="{{route('dealer.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                    <span class="title">Dealer</span>
                </a>
            </li>
            
            <li class="nav-item" style="display: none">
                <a class="sidebar-link" href="{{route('distribution.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                    <span class="title">Dealer Distribution</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('rsm.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-user"></i> </span>
                    <span class="title">Rsm</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('imei.check')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-barcode"></i> </span>
                    <span class="title">IMEI</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="sidebar-link" href="{{route('product.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-tachometer"></i> </span>
                    <span class="title">Product</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('employee.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                    <span class="title">Employee</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('zone.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-server"></i> </span>
                    <span class="title">Zone</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('retailer.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                    <span class="title">Retailer</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('bpromoter.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-user"></i> </span>
                    <span class="title">Brand Promoter</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('bpromoter.focus_model_to_bp')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-user"></i> </span>
                    <span class="title">Focus Model To BP</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('user.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                    <span class="title">User</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('menu')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-bars"></i> </span>
                    <span class="title">Menu</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('incentive.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-cube"></i> </span>
                    <span class="title">Incentive</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="{{route('report.dashboard')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                    <span class="title">Report</span>
                </a>
            </li>
			
			<li class="nav-item">
                <a class="sidebar-link" href="{{route('banner.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-image"></i> </span>
                    <span class="title">Slider Banner</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('promoOffer.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-image"></i> </span>
                    <span class="title">Promo Offer</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('message.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-envelope"></i> </span>
                    <span class="title">Authority Message</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('retailer.stockForm')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-suitcase"></i> </span>
                    <span class="title">Stock Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="sidebar-link" href="{{route('imei.disputeList')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                    <span class="title">IMEI Dispute List</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="sidebar-link" href="{{route('prebooking.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                    <span class="title">Pre Booking</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="{{route('pushNotification.index')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-envelope"></i> </span>
                    <span class="title">Push Notification</span>
                </a>
            </li>

            <li class="nav-item" style="display:none">
                <a class="sidebar-link" href="{{route('employee.stock-check')}}">
                    <span class="icon-holder"><i class="c-light-blue-500 fa fa-envelope"></i> </span>
                    <span class="title">Stock Check & Send Mail</span>
                </a>
            </li> 


            <li class="nav-item">
                <a class="sidebar-link" href="{{route('user.loginLog')}}" default>
                    <span class="icon-holder"><i class="c-blue-500 fa fa-users"></i> </span>
                    <span class="title">User Log</span>
                </a>
            </li>
        </ul>
    </div>
</div>