<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DealerInformation;
use App\Models\DelarDistribution;
use App\Models\Imei;
use Carbon\Carbon;
use Validator;
use DB;

class ImeiController extends Controller
{
    
    public function index()
    {
        return view('admin.ime.new_list');
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
	
	public function checkImei($imeNumber)
    {
        $DelarDistributionModel = new DelarDistribution;
        $DelarDistributionModel->setConnection('mysql2');
		
		$imeNumber          = str_replace(" ","%20",$imeNumber);
		
		$imeInfo = $DelarDistributionModel::
		select('barcode','barcode2','dealer_code','product_master_id','color_id','status')
		->where('barcode',$imeNumber)
		->orWhere('barcode2',$imeNumber)
		->first();

        if(isset($imeInfo) && !empty($imeInfo)) {

            $dealerInfo = DB::table('dealer_informations')
            ->select('dealer_name','dealer_address','dealer_phone_number','zone','city','division')
            ->where('dealer_code',$imeInfo->dealer_code)
            ->orWhere('alternate_code',$imeInfo->dealer_code)
            ->first();

            $status = 1;

            if($imeInfo['status'] == 0)
            {
                $salesItem = DB::table('view_sales_reports')
                ->select('customer_name','customer_phone','sale_date','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','sale_qty','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
                ->where('ime_number',$imeNumber)
                ->first();


                $customer_name         = !empty($salesItem) ? $salesItem->customer_name : "";
                $customer_phone        = !empty($salesItem) ? $salesItem->customer_phone : "";
                $product_model         = !empty($salesItem) ? $salesItem->product_model : "";
                $product_color         = !empty($salesItem) ? $salesItem->product_color : "";
                $sale_date             = !empty($salesItem) ? $salesItem->sale_date : "";
                $msrp_price            = !empty($salesItem) ? number_format($salesItem->msrp_price,2) : "";
                $sale_price            = !empty($salesItem) ? number_format($salesItem->sale_price,2) : "";
                $sale_qty              = !empty($salesItem) ? $salesItem->sale_qty : "";
                $retailer_name         = !empty($salesItem) ? $salesItem->retailer_name : "";
                $retailder_address     = !empty($salesItem) ? $salesItem->retailder_address : "";
                $retailer_phone_number = !empty($salesItem) ? $salesItem->retailer_phone_number:"";
                $bp_name               = !empty($salesItem) ? $salesItem->bp_name : "";
                $bp_phone              = !empty($salesItem) ? $salesItem->bp_phone : "";

                $dealer_name           = !empty($dealerInfo) ? $dealerInfo->dealer_name : "";
                $dealer_address        = !empty($dealerInfo) ? $dealerInfo->dealer_address : "";
                $dealer_phone          = !empty($dealerInfo) ? $dealerInfo->dealer_phone_number : "";

                $imeiInfo = "<tr><th>Status</th><td><span class='peer'><span class='badge badge-pill fl-l badge-danger lh-0 p-10 all-view-notification'>Sold</span></span></td></tr><tr><th>Customer Name</th><td>".$customer_name."</td></tr><tr><th>Customer Phone</th><td>".$customer_phone."</td></tr><tr><th>Model</th><td>".$product_model."</td></tr><tr><th>Color</th><td>".$product_color."</td></tr><tr><th>Sales Date</th><td>".$sale_date."</td></tr><tr><th>Msrp Price</th><td>".$msrp_price."</td></tr><tr><th>Sale Price</th><td>".$sale_price."</td></tr><tr><th>Qty</th><td>".$sale_qty."</td></tr><tr><th>Dealer Name</th><td>".$dealer_name."</td></tr><tr><th>Dealer Address</th><td>".$dealer_address."</td></tr><tr><th>Dealer Phone</th><td>".$dealer_phone."</td></tr><tr><th>Retailer Name</th><td>".$retailer_name."</td></tr><tr><th>Retailer Address</th><td>".$retailder_address."</td></tr><tr><th>Retailer Phone</th><td>".$retailer_phone_number."</td></tr><tr><th>BP Name</th><td>".$bp_name."</td></tr><tr><th>BP Phone</th><td>".$bp_phone."</td></tr>";


                /*
                $imeInfo->customer_name         = !empty($salesItem) ? $salesItem->customer_name : " ";
                $imeInfo->customer_phone        = !empty($salesItem) ? $salesItem->customer_phone : " ";
                $imeInfo->product_model         = !empty($salesItem) ? $salesItem->product_model : " ";
                $imeInfo->product_color         = !empty($salesItem) ? $salesItem->product_color : " ";
                $imeInfo->sale_date             = !empty($salesItem) ? $salesItem->sale_date : " ";
                $imeInfo->msrp_price            = !empty($salesItem) ? $salesItem->msrp_price : " ";
                $imeInfo->sale_price            = !empty($salesItem) ? $salesItem->sale_price : " ";
                $imeInfo->sale_qty              = !empty($salesItem) ? $salesItem->sale_qty : " ";
                $imeInfo->retailer_name         = !empty($salesItem) ? $salesItem->retailer_name : " ";
                $imeInfo->retailder_address     = !empty($salesItem) ? $salesItem->retailder_address : " ";
                $imeInfo->retailer_phone_number = !empty($salesItem) ? $salesItem->retailer_phone_number:"";
                $imeInfo->bp_name               = !empty($salesItem) ? $salesItem->bp_name : " ";
                $imeInfo->bp_phone              = !empty($salesItem) ? $salesItem->bp_phone : " ";


                $imeInfo->dealer_name       = $dealerInfo->dealer_name;
                $imeInfo->dealer_address    = $dealerInfo->dealer_address;
                $imeInfo->dealer_phone      = $dealerInfo->dealer_phone_number;
                */

                $status = 0;
            }

            if($status == 1) {
                $dealer_name           = !empty($dealerInfo) ? $dealerInfo->dealer_name : "";
                $dealer_address        = !empty($dealerInfo) ? $dealerInfo->dealer_address : "";
                $dealer_phone          = !empty($dealerInfo) ? $dealerInfo->dealer_phone_number : "";

                $dealerInfo = "<tr><th>Status</th><td><span class='peer'><span class='badge badge-pill fl-l badge-success lh-0 p-10'>Available</span></span></td></tr><tr><th>Dealer Name</th><td>".$dealer_name."</td></tr><tr><th>Dealer Address</th><td>".$dealer_address."</td></tr><tr><th>Dealer Phone</th><td>".$dealer_phone."</td></tr>";
                Log::info('Get Dealer Info By IMEI');
                return response()->json(['status'=>'info','data'=>$dealerInfo]);
            } else {
                Log::info('Get Product Info By IMEI');
                return response()->json(['status'=>'success','data'=>$imeiInfo]);
            }
        } 
        else 
        {
            Log::error('IMEI Not Available');
            return response()->json(['status'=>'error']);
        }
    }
}
