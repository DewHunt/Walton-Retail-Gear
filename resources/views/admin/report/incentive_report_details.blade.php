@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Report Dashboard</h4>
<div class="row">
    <div class="masonry-item col-md-3 mY-10">
        <div class="bd bgc-white">
            <div class="layers">
                <div class="layer w-100">
                    <div class="list-group">
                        @include('admin.report.report_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="masonry-item col-md-9 mY-10">
        <div class="bgc-white p-20 bd">
            <div style="border-bottom: 1px solid black;">
                <h4 class="c-grey-900 text-center">{{ $report_title }} Incentive Report Details</h4>
            </div>
            @if(isset($salesIncentiveReportDetails))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Ime Number</th>
                                <th>Name</th>
                                <th>Product Model</th>
                                <th>Sale Qty</th>
                                <th>Zone Name</th>
                                <th>Incentive Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesIncentiveReportDetails as $row)
                            <tr>
                                <td>{{ ++$loop->index }}.</td>
                                <td>{{ $row->ime_number }}</td>
                                <td>
                                    @if($row->bp_name)
                                    {{ $row->bp_name }}
                                    @else
                                    {{ $row->retailer_name }}
                                    @endif
                                </td>
                                <td>{{ $row->product_model }}</td>
                                <td>{{ $row->incentive_amount }}</td>
                                <td>{{ $row->zone_name }}</td>
                                <td>{{ $row->incentive_sale_qty }}</td>
                                <td>{{ $row->start_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection