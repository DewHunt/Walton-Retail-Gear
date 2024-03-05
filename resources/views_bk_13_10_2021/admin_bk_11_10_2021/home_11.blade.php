@extends('admin.master.master')
@section('content')
<div class="row gap-20 masonry pos-r">
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

    <div class="col-md-12">
        <div class="masonry-item col-md-6 col-xs-12">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900"><strong>BP Top 10 Saler List</strong></h6>
                <div class="mT-30"><canvas id="bpTopSaller" height="220"></canvas></div>
            </div>
        </div>

        <div class="masonry-item col-md-6 col-xs-12" style="display:none">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900"><strong>BP Last 10 Saler List</strong></h6>
                <div class="mT-30"><canvas id="bpLastSaller" height="220"></canvas></div>
            </div>
        </div>
    </div>

    <div class="masonry-item col-md-6 col-xs-12">
        <div class="bgc-white p-20 bd">
            <h6 class="c-grey-900"><strong>Retailer Top 15 Saler List</strong></h6>
            <div class="mT-30"><canvas id="retailerTopSaller" height="220"></canvas></div>
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

    <div class="masonry-item col-md-12">
        <div class="bgc-white p-20 bd">
            <h4 class="c-grey-900 mB-20">User Login Activity</h4>
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
@endsection

                    