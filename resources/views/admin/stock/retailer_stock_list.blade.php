@extends('admin.master.master')
@section('content')
<style>
.webbtn {
    float: left;
    margin-left: 5px;
}
@media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        
    .select-h select.form-control:not([size]):not([multiple]) {
        height: 82px !important;
        padding: 0rem .75rem !important;
        font-size: 1.5rem !important;
    }
    .bp-col-sm {
       -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    .bp-btn-sm {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .btn-float {
        float: right;
    }
    .btncw{
        width: 350px !important;
    }
}

@media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){

    .select-h select.form-control:not([size]):not([multiple]) {
        height: 82px !important;
        padding: 0rem .75rem !important;
        font-size: 1.5rem !important;
    }
    .bp-col-sm {
       -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    .bp-btn-sm {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .btn-float {
        float: right;
    }
    .btncw{
        width: 350px !important;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .select-h select.form-control:not([size]):not([multiple]) {
        height: 82px !important;
        padding: 0rem .75rem !important;
        font-size: 1.5rem !important;
    }
    .bp-col-sm {
        -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    .bp-btn-sm {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .btn-float {
        float: right;
    }
    .btncw{
        width: 350px !important;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Stock List</h4>
<div class="row">
    <div class="masonry-item col-md-6 mY-10 masonry-col bp-col-sm">
        <div class="bgc-white p-20 bd">
            <div class="peer">
                <form method="post" action="{{ route('retailer.search-stock') }}" id="stockSearch">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6 select-h bp-col-sm">
                            <label>Client Type <span class="required">*</span></label>
                            <select class="form-control" name="client_type" id="clientType" required="">
                                <option value="">Select</option>
                                <option value="retailer">Retailer</option>
                                <option value="dealer">Dealer</option>
                                <option value="emp">Employee</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 searchId bp-col-sm">

                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" class="resultType" name="result_type" value="2">
                            <div class="bp-btn-sm">
                            <button type="submit" class="btn btn-info cur-p btn-secondary pull-left mb-2 btncw" onclick="getStockType(1)">Details</button> </div>&nbsp;&nbsp;&nbsp;
                            <div class="bp-btn-sm btn-float webbtn">
                            <button type="submit" class="btn btn-success cur-p btn-secondary btncw"  onclick="getStockType(2)">Summary</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="masonry-item col-md-6 mY-10 bp-col-sm">
        {{-- <div class="bgc-white p-5 bd"> --}}
        <div class="peer">
            <div class="form-row">
                @if(isset($responseData))
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <tr>
                        <th class="text-center" colspan="2">{{ ($clientType =='retailer' || $clientType =='emp') ? 'Retailer':'Dealer' }} Information</th>
                    </tr>
                    <tr>
                        <th>Name </th>
                        <td>{{ ($clientType =='retailer' || $clientType =='emp') ? $responseData[0]['RetailerName']:$responseData[0]['DealerName']  }}</td>
                    </tr>
                    <tr>
                        <th>Phone </th>
                        <td>
                        {{ ($clientType =='retailer' || $clientType =='emp') ? $responseData[0]['RetailerPhone']:$responseData[0]['DealerPhone']  }}</td>
                    </tr>
                    <tr>
                        <th>Address </th>
                        <td>
                            {{ ($clientType =='retailer' || $clientType =='emp') ? $responseData[0]['RetailerAddress']:$responseData[0]['DealerZone']  }}
                        </td>
                    </tr>
                    <tr>
                        <th>Owner </th>
                        <td> {{ $responseData[0]['OwnerName'] }}</td>
                    </tr>
                    <tr>
                        <th>Thana </th>
                        <td> {{ $responseData[0]['ThanaName'] }}</td>
                    </tr>
                    <tr>
                        <th>Division </th>
                        <td>{{ $responseData[0]['Division'] }}</td>
                    </tr>
                </table>
                @endif
            </div>
        </div>
        {{-- </div> --}}
    </div>
</div>


@if(isset($resultType) && $resultType == 1)
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Sl.</th>
            <th>{{ ($clientType =='retailer' || $clientType =='emp') ? 'Dealer':'Retailer'  }} Name</th>
            <th>{{ ($clientType =='retailer' || $clientType =='emp') ? 'Dealer':'Retailer'  }} Phone</th>
            <th>{{ ($clientType =='retailer' || $clientType =='emp') ? 'Dealer':'Retailer'  }} Address</th>
            <th>Owner Name</th>
            <th>Thana </th>
            <th>Division</th>
            <th>{{ ($clientType =='retailer' || $clientType =='emp') ? 'Dealer':'Retailer'  }} Zone</th>
            <th>Stock Quantity</th>
            <th>Model</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($responseData))
            @foreach($responseData as $row)
            @php $getStockInfo = checkModelStock($row['Model']) @endphp
            <tr>
                <td>{{ ++$loop->index }}.</td>
                <td>{{ ($clientType =='retailer' || $clientType =='emp') ? $row['DealerName']:$row['RetailerName']  }}</td>
                <td>{{ ($clientType =='retailer' || $clientType =='emp') ? $row['DealerPhone']:$row['RetailerPhone']  }}</td>
                <td>{{ ($clientType =='retailer' || $clientType =='emp') ? $row['District']:$row['RetailerAddress']  }}</td>
                <td>{{ $row['OwnerName'] }}</td>
                <td>{{ $row['ThanaName'] }}</td>
                <td>{{ $row['Division'] }}</td>
                <td>{{ ($clientType =='retailer' || $clientType =='emp') ? $row['DealerZone']:$row['RetailerZone']  }}</td>
                <td>{{ $row['Model'] }}</td>
                <td>{{ $row['StockQuantity'] }}</td>
                <td>
                     @if(isset($getStockInfo) && !empty($getStockInfo))
                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                            @if($row['StockQuantity'] >= $getStockInfo->yeallow_qty && $row['StockQuantity'] < $getStockInfo->default_qty)
                            <button type="button" class="btn btn-warning btn-sm blink_me">0</button>
                            @elseif($row['StockQuantity'] < $getStockInfo->yeallow_qty && $row['StockQuantity'] >= $getStockInfo->red_qty)
                            <button type="button" class="btn btn-danger btn-sm blink_me">0</button>
                            @endif
                        @else
                            @if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2)
                            <button type="button" class="btn btn-warning btn-sm blink_me">0</button>

                            @elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0)
                            <button type="button" class="btn btn-danger btn-sm blink_me">0</button>
                            @endif
                        @endif
                    @else
                        @if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2)
                        <button type="button" class="btn btn-warning btn-sm blink_me">0</button>

                        @elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0)
                        <button type="button" class="btn btn-danger btn-sm blink_me">0</button>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="11" class="text-center" style="color:red"> {{ 'Product Not Available' }}</td>
        </tr>
        @endif
    </tbody>
</table>
</div>
@endif

@if(isset($resultType) && $resultType == 2)
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Sl.</th>
            <th>Model</th>
            {{-- <th>Color</th> --}}
            <th>Stock</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($responseData))
            @foreach($responseData as $row)
            @php $getStockInfo = checkModelStock($row['Model']) @endphp
            <tr>
                <td>{{ ++$loop->index }}.</td>
                <td>{{ $row['Model'] }}</td>
               {{--  <td>{{ $row['Color'] }}</td> --}}
                <td>{{ $row['StockQuantity'] }}</td>
                <td>
                    @if(isset($getStockInfo) && !empty($getStockInfo))
                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                            @if($row['StockQuantity'] >= $getStockInfo->yeallow_qty && $row['StockQuantity'] < $getStockInfo->default_qty)
                            <button type="button" class="btn btn-warning btn-sm blink_me">0</button>
                            @elseif($row['StockQuantity'] < $getStockInfo->yeallow_qty && $row['StockQuantity'] >= $getStockInfo->red_qty)
                            <button type="button" class="btn btn-danger btn-sm blink_me">0</button>
                            @endif
                        @else
                            @if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2)
                            <button type="button" class="btn btn-warning btn-sm blink_me">0</button>

                            @elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0)
                            <button type="button" class="btn btn-danger btn-sm blink_me">0</button>
                            @endif
                        @endif
                    @else
                        @if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2)
                        <button type="button" class="btn btn-warning btn-sm blink_me">0</button>

                        @elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0)
                        <button type="button" class="btn btn-danger btn-sm blink_me">0</button>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="4" class="text-center" style="color:red"> {{ 'Product Not Available' }}</td>
        </tr>
        @endif
    </tbody>
</table>
</div>
@endif
@endsection