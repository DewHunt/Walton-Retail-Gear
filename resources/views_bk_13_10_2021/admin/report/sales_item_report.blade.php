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
                <h4 class="c-grey-900 text-center">Order Summery</h4>
            </div>

            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <th scope="row">Customer Name:</th>
                        <td>{{ $salesInfo->customer_name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Customer Phone:</th>
                        <td>{{ $salesInfo->customer_phone }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Order Id:</th>
                        <td>{{ $salesInfo->id }}</td>
                    </tr>

                    @if($salesInfo->bp_id)
                    <tr>
                        <th scope="row">BP Name:</th>
                        <td>{{ $salesInfo->bp_name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">BP Phone:</th>
                        <td>{{ $salesInfo->bp_phone }}</td>
                    </tr>
                    @endif

                    @if($salesInfo->retailer_id)
                    <tr>
                        <th scope="row">Retailer Name:</th>
                        <td>{{ $salesInfo->retailer_name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Retailer Phone:</th>
                        <td>{{ $salesInfo->retailer_phone_number }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Retailer Address:</th>
                        <td>{{ $salesInfo->retailder_address }}</td>
                    </tr>
                    @endif

                    <tr>
                        <th scope="row">Sale Date:</th>
                        <td>{{ $salesInfo->sale_date }}</td>
                    </tr>
                </tbody>
            </table>    


            @if(isset($saleProductList))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Photo</th>
                                <th>IME I Number</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Color</th>
                                <th>Msrp Price</th>
                                <th>Sale Price</th>
                                <th>Sale Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($saleProductList as $row)
                            <tr>
                                <td>{{ ++$loop->index }}.</td>
                                <td>
                                     @if(isset($row->photo) && !empty($row->photo))
                                    <img src="{{ asset('public/upload/client/'.$row->photo) }}" alt="" width="50" height="50"/></td>
                                    @else
                                    <img src="{{ asset('public/upload/no-image.png') }}" alt="" width="50" height="50"/></td>
                                    @endif
                                </td>
                                <td>{{ $row->ime_number }}</td>
                                <td>{{ $row->product_code }}</td>
                                <td>{{ $row->product_type }}</td>
                                <td>{{ $row->product_model }}</td>
                                 <td>{{ $row->product_color }}</td>
                                <td>{{ $row->msrp_price }}</td>
                                <td>{{ $row->sale_price }}</td>
                                <td>{{ $row->sale_qty }}</td>
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