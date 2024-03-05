@extends('admin.master.master')
@section('content')
<style>
    @media (min-width: 768px){
        .user-top-activity {
            margin-top: 2% !important;
        }
        .bp-top-margin {
             margin-top: 1% !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .bp-top-margin {
            margin-top: 22% !important;
        }
        .user-top-activity {
            position: relative !important;
            margin-top: 0% !important;
        }
        .bp-top-margin .col-md-6 {
            -ms-flex: 0 0 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        .bp-top-height {
            display: block !important;
        }
    }

    / Portrait and Landscape /
    @media only screen 
    and (min-device-width: 414px) 
    and (max-device-width: 736px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
        .bp-top-margin {
            margin-top: 15% !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
        .bp-top-margin {
            margin-top: 22% !important;
        }
        .user-top-activity {
            position: relative !important;
            margin-top: 0% !important;
        }
        .bp-top-margin .col-md-6 {
            -ms-flex: 0 0 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        .bp-top-height {
           display: block !important;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .bp-top-margin {
            margin-top: 22% !important;
        }
        .user-top-activity {
            position: relative !important;
            margin-top: 0% !important;
        }
        .bp-top-margin .col-md-6 {
            -ms-flex: 0 0 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        .bp-top-height {
           display: block !important;
        }
    }
</style>

<div class="col-md-12">
    <div class="row gap-20 masonry pos-r bp-top-height">
        <div class="masonry-sizer col-md-6 pos-a"></div>
        <div class="masonry-item col-md-12">
            <div class="bgc-white p-20 bd">
                <div class="mT-30"><div id="monthlySalesChart"></div></div>
            </div>
        </div>

        <div class="masonry-item col-md-12">
            <div class="bgc-white p-20 bd">
                <div class="mT-30"><div id="yearMonthlySalesList" height="220"></div></div>
            </div>
        </div>

        <div class="masonry-item col-md-12">
            <div class="bgc-white p-20 bd">
                <div class="mT-30"><div id="modelWaiseSalesChart"></div></div>
            </div>
        </div>


        <div class="col-md-12 bp-top-margin">
            <div class="row">
                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900"><strong>BP Top 10 Saler List</strong></h6>
                        <div class="mT-30"><canvas id="bpTopSaller" height="220"></canvas></div>
                    </div>
                </div>

                <div class="masonry-item col-md-6" style="display: none;">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900"><strong>BP Last 10 Saler List</strong></h6>
                        <div class="mT-30"><canvas id="bpLastSaller" height="220"></canvas></div>
                    </div>
                </div>

                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900"><strong>Retailer Top 15 Saler List</strong></h6>
                        <div class="mT-30"><canvas id="retailerTopSaller" height="220"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <!--
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900">Example Chart Contailer</h6>
                <div class="mT-30"><div id="userlists" height="220"></div></div>
            </div>
        </div>
    
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <div class="mT-30"><div id="chartContainer"></div></div>
            </div>
        </div>
    
    
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <div class="mT-30"><div id="linechart"></div></div>
            </div>
        </div>
        -->

        <div class="masonry-item col-md-12 user-top-activity">
            <div class="bgc-white p-20 bd">
                <h4 class="c-grey-900 mB-20">User Login Activity</h4>
                <div style="height: 600px;overflow: scroll;">
                <table class="table table-bordered table-sm table-striped" style="width: 100%;">
                    <thead class="text-center" style="background-color:#a9a9a9;color: #ffffff;">
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Event Type</th>
                            <th scope="col">User Agent</th>
                            <th scope="col">IP Address</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loginLogList as $activity)
                        <tr>
                            <th scope="row">{{ ++$loop->index }}.</th>
                            <td>{{ $activity->name }}</td>
                            <td>{{ $activity->type }}</td>
                            <td>{{ $activity->user_agent }}</td>
                            <td>{{ $activity->ip_address }}</td>
                            <td><span class="c-green-500">{{ date('d M Y h:i:s a',strtotime($activity->created_at)) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

