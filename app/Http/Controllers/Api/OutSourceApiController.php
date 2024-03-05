<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use lluminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\DealerInformation;
use App\Models\DelarDistribution;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use App\Models\Rsm;
use App\Models\Products;
use App\Models\ProductMasterPrice;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\SpecialAward;
use App\Models\BrandPromoter;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\BpAttendance;
use App\Models\AuthorityMessage;
use App\Models\PreBookin;
use App\Models\PushNotification;
use Carbon\Carbon;
use DB;
use Response;
use Validator;
use Image;
use JWTAuth;
use Storage;
use Tymon\JWTAuth\Exceptions\JWTException;
date_default_timezone_set('Asia/Dhaka');
class OutSourceApiController extends Controller
{
    
    public function verifyApiAuth($headerAuth,$token)
    {
        if($headerAuth) {
            if ($tokenFetch = JWTAuth::parseToken()->authenticate()) {
                if($token) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }
    
    public function GetByInfoImeNumber(Request $request,$imeListArray)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));

        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true)  
        {
            $response   = "";
            $imeResult  = [];

            $imeNumberList    = explode(",",$imeListArray);
            
            foreach($imeNumberList as $imeNumber) 
            {

                $checkValidIMEI = strlen($imeNumber);
                if($checkValidIMEI != 15) 
                {
                    Log::error('Invalid IMEI Number ->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404,"Invalid IMEI Number"),404);
                }

                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetIMEIinfo"),$imeNumber),"GET");
                $responseData       = (array) json_decode($getCurlResponse['response_data'],true);

                if(isset($responseData) && $responseData == "" || empty($responseData)) {
                    Log::error('IMEI Info Not Found ->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404),404);
                }

                $imeInfo = [
                    'barcode'=>$responseData[0]['ImeiOne'],
                    'barcode2'=>$responseData[0]['ImeiTwo'],
                    'dealer_code'=>$responseData[0]['DealerCode'],
                    'distributor_name'=>$responseData[0]['DistributorNameCellCom'],
                    'retailerName'=>$responseData[0]['RetailerName'],
                    'retailerPhone'=>$responseData[0]['RetailerPhone'],
                    'retailerAddress'=>$responseData[0]['RetailerAddress'],
                    'retailerZone'=>$responseData[0]['RetailerZone'],
                    'dealerZone'=>$responseData[0]['DealerZone'],
                    'productModel'=>$responseData[0]['Model'],
                    'productColor'=>$responseData[0]['Color'],
                    'is_sold'=>($responseData[0]['IsSoldOut'] == true) ? true:false,
                    'productId'=>$responseData[0]['ProductID'],
                    'status'=>($responseData[0]['IsSoldOut'] == true) ? "0":"1",
                ];

                $productId = $responseData[0]['ProductID'] ? $responseData[0]['ProductID']:0;

                
                if(isset($responseData) && !empty($responseData) || $responseData != "") 
                {
                    $imeProductResult = DB::table('view_product_master')
                    ->where('product_id','=',$productId)
                    ->first();

                    if(isset($imeProductResult) && empty($imeProductResult) || $imeProductResult == "")
                    {
                        Log::warning('Product Not Available By imei Number->OutSourceApiController->GetByInfoImeNumber');
                        return response()->json(apiResponses(404,"Product Not Available"),404);
                    }

                    $imeInfo['productCode']  =    $imeProductResult->product_code;
                    $imeInfo['productType']  =    $imeProductResult->product_type;
                    $imeInfo['category']     =    $imeProductResult->category2;
                    $imeInfo['mrpPrice']     =    $imeProductResult->mrp_price;
                    $imeInfo['msdpPrice']    =    $imeProductResult->msdp_price;
                    $imeInfo['msrpPrice']    =    $imeProductResult->msrp_price;

                    $productColorId = DB::table('colors')
                    ->where('name','like','%'.$responseData[0]['Color'].'%')
                    ->value('color_id');

                    $dealerName    = DB::table('dealer_informations')
                    ->where('dealer_code',$responseData[0]['DealerCode'])
                    ->orWhere('alternate_code',$responseData[0]['DealerCode'])
                    ->value('dealer_name');
                    
                    $imeInfo['dealerName']   = $dealerName;
                    $imeInfo['color_id']     = $productColorId ? $productColorId:0;

                    $responseArray = $imeInfo;
                    return response()->json($imeInfo);
                } 
                else 
                {
                    Log::warning('Product Not Found By imei Number->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404),404);//Data not found
                } 
            }
        } 
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    
    public function GetByInfoImeNumber_bk_07_09_2021(Request $request,$imeListArray)
    {
       
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));

        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true){

            $response   = "";
            $imeResult  = [];

            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');

            $imeNumberList    = explode(",",$imeListArray);
            
            foreach($imeNumberList as $imeNumber) {

                $checkValidIMEI = strlen($imeNumber);
                if($checkValidIMEI != 15) {
                    Log::error('Invalid IMEI Number ->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404,"Invalid IMEI Number"),404);
                }

                $imeInfo = $DelarDistributionModel::
                select('barcode','barcode2','dealer_code','color_id','status')
                ->where('barcode',$imeNumber)
                ->orWhere('barcode2',$imeNumber)
                ->first();

                if(isset($imeInfo) && $imeInfo == "" || empty($imeInfo)) {
                    Log::error('IMEI Info Not Found ->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404),404);
                }
                
                $sold_status = false;
                if(isset($imeInfo['status']) && $imeInfo['status'] == 0) {
                    $sold_status = true;
                }

                if(isset($imeInfo) && !empty($imeInfo) || $imeInfo != "") {
                    $productMasterId = $DelarDistributionModel::
                    select('product_master_id')
                    ->where('barcode',$imeNumber)
                    ->orWhere('barcode2',$imeNumber)
                    ->value('product_master_id');

                    $imeProductResult = DB::table('view_product_master')
                    ->where('product_master_id','=',$productMasterId)
                    ->first();
                    if(isset($imeProductResult) && empty($imeProductResult) || $imeProductResult == "")
                    {
                        Log::warning('Product Not Available By imei Number->OutSourceApiController->GetByInfoImeNumber');
                        return response()->json(apiResponses(404,"Product Not Available"),404);
                    }

                    $productColor = DB::table('colors')
                    ->where('color_id',$imeInfo->color_id)
                    ->value('name');

                    $dealerName    = DB::table('dealer_informations')
                    ->where('dealer_code',$imeInfo->dealer_code)
                    ->orWhere('alternate_code',$imeInfo->dealer_code)
                    ->value('dealer_name');
                    
                    $imeInfo->productId    =    $imeProductResult->product_id;
                    $imeInfo->productCode  =    $imeProductResult->product_code;
                    $imeInfo->productType  =    $imeProductResult->product_type;
                    $imeInfo->productModel =    $imeProductResult->product_model;
                    $imeInfo->category     =    $imeProductResult->category2;
                    $imeInfo->mrpPrice     =    $imeProductResult->mrp_price;
                    $imeInfo->msdpPrice    =    $imeProductResult->msdp_price;
                    $imeInfo->msrpPrice    =    $imeProductResult->msrp_price;

                    $imeInfo->productColor =    $productColor;
                    $imeInfo->dealerName   =    $dealerName;
                    $imeInfo->dealerCode   =    $imeInfo->dealer_code;
                    $imeInfo->is_sold      =    $sold_status;

                    $responseArray = $imeInfo;
                    return response()->json($imeInfo);
                    
                } else {
                    Log::warning('Product Not Found By imei Number->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404),404);//Data not found
                } 
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getImeList(Request $request)
    {
        
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {

            $notFoundIme = [];
            $imeResult   = [];
            $imeStatus   = [];
            $imeInfo     = [];

            $imeList    = $request->input('imeList');
            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');

            $getAllImeNumber = $DelarDistributionModel::
            select('barcode','barcode2')
            ->get()
            ->pluck('barcode','barcode2')
            ->toArray();

            if(is_array($imeList)) {
                foreach($imeList as $imeNumber) {
                    if(!in_array($imeNumber, $getAllImeNumber)) {
                        $notFoundIme[] = $imeNumber;
                        $imeStatus[$imeNumber] = "Not Found";
                    }
                    else 
                    {
                        $imeInfo = $DelarDistributionModel::
                        select('barcode','barcode2','dealer_code')
                        ->where('barcode',$imeNumber)
                        ->orWhere('barcode2',$imeNumber)
                        ->first();

                        $productMasterId = $DelarDistributionModel::
                        select('product_master_id')
                        ->where('barcode',$imeNumber)
                        ->orWhere('barcode2',$imeNumber)
                        ->value('product_master_id');

                        $imeProductResult = DB::table('view_product_master')
                        ->where('product_master_id',$productMasterId)
                        ->first();

                        $imeInfo->imeProductInfo = $imeProductResult;

                        array_push($imeResult,$imeInfo);
                        $imeStatus[$imeNumber] = "Match";
                    }
                }
            }

            if(!empty($notFoundIme)) {
                Log::info('Get IMEI List By Apps');
                return response()->json([$imeResult,$notFoundIme],200);
            } else {
                Log::info('Get IMEI List By Apps');
                return response()->json([$imeResult],200);
            }
        }
        else 
        {
            return response()->json($response = apiResponses(401),401);
        }

    }
    
    public function SalesProduct(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;
            $groupId   = 0;

            if($request->input('bp_id')) 
            {
                $bpId       = $request->input('bp_id');
                $groupId    = 1;
            } 
            else 
            {
                $retailId   = $request->input('retailer_id');
                $groupId    = 2;
            }

            $customer_name  = $request->input('customer_name');
            $customer_phone = $request->input('customer_phone');
            $itemList       = $request->input('list');
            
            $itemList=(!empty($itemList))?json_decode($itemList,true):[];

            if(empty($itemList) || !is_array($itemList)) {
                return response()->json(apiResponses(400,'Cart is empty'),400);
            }

            $imeProductStatus  = [];
            $imeProductResult  = "";
            $saleStatus        = false;

            $sale_data = "";

            $saleId = "";
            foreach ($itemList as $lists) 
            {
                $getCurlResponse = getData(sprintf(RequestApiUrl("GetIMEIinfo"),$lists['ime_number']),"GET");
                $responseData    = (array) json_decode($getCurlResponse['response_data'],true);

                if(isset($responseData) && $responseData == "" || empty($responseData)) {
                    Log::error('IMEI Not Found ->OutSourceApiController->SalesProduct');
                    return response()->json(apiResponses(404),404);
                }

                $productStatus  = ($responseData[0]['IsSoldOut'] == true) ? "0":"1";
                $dealerCode     = $responseData[0]['DealerCode'];
                $productId      = $responseData[0]['ProductID'];

                $imeProductResult = DB::table('view_product_master')
                ->where('product_id','=',$productId)
                ->first();

                $productMasterId    = $imeProductResult->product_master_id;
                $productColor       = $responseData[0]['Color'];
                
                $ZoneId    = 0;
                if($groupId == 1)
                {
                    $dealerZoneName = $responseData[0]['DealerZone'];
                    if(!empty($dealerZoneName)) {
                        $ZoneId = DB::table('zones')
                        ->where('zone_name','like','%'.$dealerZoneName.'%')
                        ->value('id');
                    }
                }
                else
                {
                    $getZoneId = DB::table('retailers')
                    ->where('retailer_id',$retailId)
                    ->value('zone_id');

                    if($getZoneId != null || !empty($getZoneId)) {
                        $ZoneId = $getZoneId;
                    }
                }

                if($productStatus == 1 && $productMasterId > 0) 
                {
                    if($saleStatus === false) 
                    {
                        $ClientPic = "";
                        if($request->hasFile('photo')) {
                            $getPhoto = $request->file('photo');
                            $filename = time().'.'.$getPhoto->getClientOriginalExtension();
                            $destinationPath = public_path('/upload/client');
                            $success = $getPhoto->move($destinationPath, $filename);
                        
                            $ClientPic = $filename;
                        }
                        
                        $baseUrl        = URL::to('');
                        $storagePath    = $baseUrl.'/storage/app/public/'.$ClientPic;
                        
                        Sale::create([
                            "customer_name"=>$request->input('customer_name'),
                            "customer_phone"=>$request->input('customer_phone'),
                            "bp_id"=> $bpId,
                            "retailer_id"=> $retailId,
                            "dealer_code"=> $dealerCode,
                            "sale_date"=>date('Y-m-d H:i:s'),
                            "photo"=>$ClientPic,
                            "status"=>0
                        ]);
                        $saleId = DB::getPdo()->lastInsertId();
                        $saleStatus = true;
                    }

                    if(!empty($saleId)) 
                    {
                        SaleProduct::create([
                            "sales_id"=>$saleId,
                            "ime_number"=>$lists['ime_number'],
                            "product_master_id"=>$productMasterId,
                            "dealer_code"=>$dealerCode,
                            "product_id"=>$imeProductResult->product_id,
                            "product_code"=>$imeProductResult->product_code,
                            "product_type"=>$imeProductResult->product_type,
                            "product_model"=>$imeProductResult->product_model,
                            "product_color"=>$productColor ? $productColor:'Others',
                            "category"=>$imeProductResult->category2,
                            "mrp_price"=>$imeProductResult->mrp_price,
                            "msdp_price"=>$imeProductResult->msdp_price,
                            "msrp_price"=>$imeProductResult->msrp_price,
                            "sale_price"=>$lists['price'],
                            "sale_qty"=>$lists['qty'],
                            "bp_id"=>$bpId,
                            "retailer_id"=>$retailId,
                            "product_status"=>0 //Sold Order
                        ]);
                        //Ime Database Product Status Update Start
                        $getCurlResponse = getData(sprintf(RequestApiUrl("UpdateIMEIStatus"),$lists['ime_number']),"GET");
                    }

                    $sale_data = [
                        "sale_id"=>$saleId,
                        "bp_id"=> $bpId,
                        "retailer_id"=> $retailId,
                        "sale_date"=>date('Y-m-d H:i:s'),
                        "customer_name"=> $request->input('customer_name'),
                        "customer_phone"=>  $request->input('customer_phone')
                    ];
                    
                    $saleQty        = $lists['qty'];
                    $saleId         = $saleId;
                    $sale_date      = date('Y-m-d');

                    $incentiveType  = $groupId == 1 ? "bp":$retailId;

                    $incentiveLists = DB::table('incentives')
                    ->where('incentive_group',$groupId)
                    ->where('start_date','<=',$sale_date)
                    ->where('end_date','>=',$sale_date)
                    ->where('status',1)
                    ->get();

                    if($incentiveLists->isNotEmpty()) 
                    {
                        foreach($incentiveLists as $incentive)
                        {
                            $getModelId         = json_decode($incentive->product_model,TRUE);
                            $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                            $getZone            = json_decode($incentive->zone,TRUE);
                            $minQty             = $incentive->min_qty;

                            $totalSaleQty = DB::table('view_sales_reports')
                            ->where('product_master_id',$productMasterId)
                            //->whereBetween('sale_date',[$start_date,$end_date])
                            ->sum('view_sales_reports.sale_qty');

                            if(in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                                if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                                    if(in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                                        if($totalSaleQty >= $minQty) {
                                            DB::table('sale_incentives')
                                            ->insert([
                                                "group_category_id"=>$incentive->group_category_id,
                                                "incentive_category"=>$incentive->incentive_category,
                                                "ime_number"=>$lists['ime_number'],
                                                "sale_id" =>$saleId, 
                                                "bp_id" =>$bpId,
                                                "retailer_id"=>$retailId,
                                                "incentive_title"=>$incentive->incentive_title,
                                                "product_model"=>$responseData[0]['Model'],
                                                "zone"=>$incentive->zone,
                                                "incentive_amount"=>$incentive->incentive_amount,
                                                "incentive_min_qty"=>$incentive->min_qty,
                                                "incentive_sale_qty"=>$saleQty,
                                                "total_amount"=>$saleQty*$incentive->incentive_amount,
                                                "start_date"=>$incentive->start_date,
                                                "end_date"=>$incentive->end_date,
                                                "incentive_date"=>date('Y-m-d'),
                                                "incentive_status"=>$incentive->status
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else 
                {
                    $saleRemove = DB::table('sales')
                    ->where('id',$saleId)
                    ->delete();

                    $saleItemsRemove = DB::table('sale_products')
                    ->where('sales_id',$saleId)
                    ->delete();

                    //Ime Database Product Status Update Start
                    $DelarDistributionModel::
                    where('barcode',$lists['ime_number'])
                    ->orWhere('barcode2',$lists['ime_number'])
                    ->update([
                    "status"=>1,
                    ]);

                    $notFoundIme[] = $lists['ime_number'];
                    return response()->json(apiResponses(404,'Product Not Found'),404);
                }
            }

            if(isset($sale_data) && !empty($sale_data))
            {
                Log::info('Product Sales Success By Apps');
                return response()->json($sale_data,200);//Success
            }
        } 
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function SalesProduct_bk_07_09_2021(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $jsonFeedArray = $request->input('sales');
            $getJsonFeed   = json_decode($jsonFeedArray);
            
             DB::table('temporaryes')->insert([
                "request_data"=>$jsonFeedArray,
                "date"=>date('d-m-Y'),
            ]);

            exit();
            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');

            $bpId      = 0;
            $retailId  = 0;
            $groupId   = 0;

            if($request->input('bp_id')) {
                $bpId           = $request->input('bp_id');
                $groupId        = 1;

            } else {
                $retailId       = $request->input('retailer_id');
                $groupId        = 2;
            }

            $customer_name  = $request->input('customer_name');
            $customer_phone = $request->input('customer_phone');
            $itemList       = $request->input('list');
            
            $itemList=(!empty($itemList))?json_decode($itemList,true):[];

            if(empty($itemList) || !is_array($itemList)) {
                return response()->json(apiResponses(400,'Cart is empty'),400);
            }

            $imeProductStatus  = [];
            $imeProductResult  = "";
            $saleStatus        = false;

            $sale_data = "";

            $saleId = "";
            foreach ($itemList as $lists) {
                $getImeResult = $DelarDistributionModel::
                where('barcode',$lists['ime_number'])
                ->orWhere('barcode2',$lists['ime_number'])
                ->first();

                if(isset($getImeResult)) 
                {
                    $productStatus   = $getImeResult['status'];
                    $productMasterId = $getImeResult['product_master_id'];
                    $dealerCode      = $getImeResult['dealer_code'];

                    $productColor = DB::table('colors')
                    ->where('color_id',$getImeResult['color_id'])
                    ->value('name');
                    
                    
                    $ZoneId    = 0;
                    if($groupId == 1)
                    {
                        $dealerZoneName = DB::table('dealer_informations')
                        ->where('dealer_code',$dealerCode)
                        ->where('alternate_code',$dealerCode)
                        ->value('zone');

                        if(!empty($dealerZoneName)) {
                            $ZoneId = DB::table('zones')
                            ->where('zone_name','like','%'.$dealerZoneName.'%')
                            ->value('id');
                        }
                    }
                    else
                    {
                        $getZoneId = DB::table('retailers')
                        ->where('retailer_id',$retailId)
                        ->value('zone_id');

                        if($getZoneId != null || !empty($getZoneId)) {
                            $ZoneId = $getZoneId;
                        }
                    }
                    

                    if($productStatus == 1 && $productMasterId > 0) {
                        $imeProductResult = DB::table('view_product_master')
                        ->where('product_master_id',$productMasterId)
                        ->first();
                        
                        if($imeProductResult) {
                            //$imeProductStatus[] = true;
                            if($saleStatus === false) {
                                $ClientPic = "";
                                if($request->hasFile('photo')) {
                                    $getPhoto = $request->file('photo');
                                    $filename = time().'.'.$getPhoto->getClientOriginalExtension();
                                    $destinationPath = public_path('/upload/client');
                                    $success = $getPhoto->move($destinationPath, $filename);
                                
                                    $ClientPic = $filename;
                                }
                                
                                $baseUrl        = URL::to('');
                                $storagePath    = $baseUrl.'/storage/app/public/'.$ClientPic;
                                
                                
                                
                                Sale::create([
                                    "customer_name"=> $request->input('customer_name'),
                                    "customer_phone"=>  $request->input('customer_phone'),
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "dealer_code"=> $dealerCode,
                                    "sale_date"=>date('Y-m-d H:i:s'),
                                    "photo"=> $ClientPic, //$storagePath,
                                    "status"=>0
                                ]);
                                $saleId = DB::getPdo()->lastInsertId();
                                $saleStatus = true;
                            }

                            if(!empty($saleId)) {
                                SaleProduct::create([
                                    "sales_id"=>$saleId,
                                    "ime_number"=> $lists['ime_number'],
                                    "product_master_id"=> $productMasterId,
                                    "dealer_code"=> $dealerCode,
                                    "product_id"=> $imeProductResult->product_id,
                                    "product_code"=>  $imeProductResult->product_code,
                                    "product_type"=> $imeProductResult->product_type,
                                    "product_model"=> $imeProductResult->product_model,
                                    "product_color"=> $productColor ? $productColor:'Others',
                                    "category"=> $imeProductResult->category2,
                                    "mrp_price"=> $imeProductResult->mrp_price,
                                    "msdp_price"=> $imeProductResult->msdp_price,
                                    "msrp_price"=> $imeProductResult->msrp_price,
                                    "sale_price"=> $lists['price'],
                                    "sale_qty"=> $lists['qty'],
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "product_status"=>0 //Sold Order
                                ]);
                                //Ime Database Product Status Update Start
                                $DelarDistributionModel::
                                where('barcode',$lists['ime_number'])
                                ->orWhere('barcode2',$lists['ime_number'])
                                ->update([
                                    "status"=>0,
                                ]);
                            }

                            $sale_data = [
                                "sale_id"=>$saleId,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "sale_date"=>date('Y-m-d H:i:s'),
                                "customer_name"=> $request->input('customer_name'),
                                "customer_phone"=>  $request->input('customer_phone')
                            ];
                            
                            $saleQty        = $lists['qty'];
                            $saleId         = $saleId;
                            $sale_date      = date('Y-m-d');

                            $incentiveType  = $groupId == 1 ? "bp":$retailId;

                            $incentiveLists = DB::table('incentives')
                            ->where('incentive_group',$groupId)
                            ->where('start_date','<=',$sale_date)
                            ->where('end_date','>=',$sale_date)
                            ->where('status',1)
                            ->get();

                            if($incentiveLists->isNotEmpty()) {
                                foreach($incentiveLists as $incentive)
                                {
                                    $getModelId         = json_decode($incentive->product_model,TRUE);
                                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                                    $getZone            = json_decode($incentive->zone,TRUE);
                                    $minQty             = $incentive->min_qty;

                                    $totalSaleQty = DB::table('view_sales_reports')
                                    ->where('product_master_id',$productMasterId)
                                    //->whereBetween('sale_date',[$start_date,$end_date])
                                    ->sum('view_sales_reports.sale_qty');

                                    if(in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                                        if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                                            if(in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                                                if($totalSaleQty >= $minQty) {
                                                    DB::table('sale_incentives')
                                                    ->insert([
                                                        "group_category_id"=>$incentive->group_category_id,
                                                        "incentive_category"=>$incentive->incentive_category,
                                                        "ime_number"=>$lists['ime_number'],
                                                        "sale_id" =>$saleId, 
                                                        "bp_id" =>$bpId,
                                                        "retailer_id"=>$retailId,
                                                        "incentive_title"=>$incentive->incentive_title,
                                                        "product_model"=>$imeProductResult->product_model,
                                                        "zone"=>$incentive->zone,
                                                        "incentive_amount"=>$incentive->incentive_amount,
                                                        "incentive_min_qty"=>$incentive->min_qty,
                                                        "incentive_sale_qty"=>$saleQty,
                                                        "total_amount"=>$saleQty*$incentive->incentive_amount,
                                                        "start_date"=>$incentive->start_date,
                                                        "end_date"=>$incentive->end_date,
                                                        "incentive_date"=>date('Y-m-d'),
                                                        "incentive_status"=>$incentive->status
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                }
                            } 
                        }
                        else
                        {
                            $saleRemove = DB::table('sales')
                            ->where('id',$saleId)
                            ->delete();

                            $saleItemsRemove = DB::table('sale_products')
                            ->where('sales_id',$saleId)
                            ->delete();

                            //Ime Database Product Status Update Start
                            $DelarDistributionModel::
                            where('barcode',$lists['ime_number'])
                            ->orWhere('barcode2',$lists['ime_number'])
                            ->update([
                                "status"=>1,
                            ]);

                            $notFoundIme[] = $lists['ime_number'];
                            //$response = apiResponses(301,$notFoundIme);//Data Not Found
                            //return response()->json(["message"=> "Ime Not Found.Please Contact Your Authority","not_found_ime"=>$notFoundIme,"code"=>404],404);
                            return response()->json(apiResponses(404,'Product Not Found'),404);
                        }
                    }
                    else 
                    {
                        return response()->json(apiResponses(422,'Product All Ready Sold'),422);
                    }
                }
                else
                {
                    Log::warning('Invalid Ime Number ->OutSourceApiController->SalesProduct');
                    return response()->json(apiResponses(422,'Invalid Ime Number'),422);
                }

            }

            if(isset($sale_data) && !empty($sale_data))
            {
                Log::info('Product Sales Success By Apps');
                return response()->json($sale_data,200);//Success
            }
        } 
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function salesReport(Request $request)
    {
        $authenticateUser   = $this->getAuthenticatedUser();
        $headerAuth         = $request->header('Authorization'); 
        $token              = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus       = $this->verifyApiAuth($headerAuth,$token);


        if(isset($verifyStatus) && $verifyStatus === true) {
            
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $loginUserId      = 0;
            if($userExists->bp_id > 0) {
                $loginUserId = $userExists->bp_id;
            } else if($userExists->retailer_id > 0) {
                $loginUserId = $userExists->retailer_id;
            }

            $bpId      = 0;
            $retailId  = 0;
            if($request->bpId > 0) {
                $getbpId        = $request->bpId;
                $bpId           = $getbpId ? $getbpId:$loginUserId;
            } else {
                $getretailId  = $request->retailerId;
                $retailId      = $getretailId ? $getretailId:$loginUserId;
                //$retailId      = $loginUserId;
            }

            $current_month_first_date       =  date('Y-m-01');
            $current_month_last_date        =  date('Y-m-t');

            $fast_day_previous_one_month    = date('Y-m-01', strtotime('-1 Months'));
            $last_day_previous_one_month    = date('Y-m-t', strtotime('-1 Months'));

            $fast_day_previous_two_month    = date('Y-m-01', strtotime('-2 Months'));
            $last_day_previous_two_month    = date('Y-m-t', strtotime('-2 Months'));


            $compaireStartDate  = strtotime($fast_day_previous_two_month);
            $compaireEndDate    = strtotime($current_month_last_date);
            $searchStartDate    = strtotime($request->startDate);
            $searchEndDate      = strtotime($request->endDate);

            $reqSdate       = $request->startDate;
            $reqEdate       = $request->endDate;

            if($searchStartDate >= $compaireStartDate  && $searchEndDate <= $compaireEndDate) {

                $saleList = DB::table('sales')
                ->where('bp_id',$bpId)
                ->where('retailer_id',$retailId)
                ->whereBetween('sale_date',[$reqSdate,$reqEdate])
                ->get();

                foreach($saleList as $sale) {
                    
                    $saleProductList = DB::table('sale_products')
                    //->select('*')
                    ->select('ime_number','product_code','product_type','product_model','product_color','category','mrp_price','msdp_price','msrp_price','sale_price','sale_qty')
                    ->where('bp_id',$bpId)
                    ->where('retailer_id',$retailId)
                    ->where('sales_id',$sale->id)
                    ->get();

                    $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();

                    foreach ($saleProductList as $saleProduct) {
                        if(isset($dealerInfo)) {
                            $saleProduct->dealer_name = $dealerInfo->name;
                            $saleProduct->dealer_code = $dealerInfo->code ? $dealerInfo->code : $dealerInfo->alternate_code;
                        }
                        else
                        {
                            $saleProduct->dealer_name = "";
                            $saleProduct->dealer_code = "";
                        }
                        
                     //   $sale->product_list = $saleProduct;
                    }
                    $sale->product_list=$saleProductList;
                    if($sale->retailer_id) {
                        $retailerInfo = DB::table('retailers')
                        ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                        ->where('retailer_id',$sale->retailer_id)
                        ->first();

                        $sale->retailer_info=$retailerInfo;
                    }
                    else{
                        $sale->retailer_info="";
                    }

                    if(isset($bpId) && $bpId > 0)
                    {
                        $brandPromoterInfo = DB::table('brand_promoters')
                        ->select('bp_name as name','bp_phone as phone')
                        ->where('bp_id',$bpId)
                        ->first();

                        $sale->bp_info=$brandPromoterInfo;
                    }
                    else{
                        $sale->bp_info="";
                    }
                }

                if(count($saleList) > 0) {
                    Log::info('Get Sales List By Apps Request');
                    return response()->json($saleList,200);
                } else {
                    Log::warning('Sales List Not Found By Apps Request Date Range ->OutSourceApiController->salesReport');
                    return response()->json(apiResponses(404),404);
                }

            }
            else{
                Log::warning('Sales List Not Found By Apps Request Date Range ->OutSourceApiController->salesReport');
                return response()->json(["message"=> "Date Range Not Coverage.Please Try Again[".$reqSdate.'/'.$reqEdate."]","code"=>404],404);
            }

        }
        else {
            return response()->json(apiResponses(401),401);
        }
        
    }

    public function singleSalesReport(Request $request)
    { 
        $authenticateUser = $this->getAuthenticatedUser();
        $headerAuth       = $request->header('Authorization'); 
        $token            = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus     = $this->verifyApiAuth($headerAuth,$token);

        $bpId      = 0;
        $retailId  = 0;
        if($request->bpId) {
            $bpId           = $request->bpId;
        } else {
            $retailId       = $request->retailerId;
        }
        
        $sale_id  = $request->salesId;

        if(isset($verifyStatus) && $verifyStatus === true && $sale_id > 0) 
        {
            $salesLists = DB::table('sales')
            ->select('*')
            //->where($authenticateUser['login_field'],$authenticateUser['id'])
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->where('id',$sale_id)
            ->first();

            if(isset($salesLists) && !empty($salesLists)) 
            {
                $saleProductList = DB::table('sale_products')
                ->select('*')
                //->where($authenticateUser['login_field'],$authenticateUser['id'])
                ->where('bp_id',$bpId)
                ->where('retailer_id',$retailId)
                ->where('sales_id',$salesLists->id)
                ->get();
                $salesLists->saleProductList = $saleProductList;
                
                Log::info('Get Sales List');
                return response()->json($salesLists,201);
            }
            else 
            {
                Log::info('Sales List Not Found');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function salesIncentiveReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $bpId        = 0;
            $retailerId  = 0;

            if($request->bpId > 0) {
                $bpId        = $request->bpId;
            } else {
                $retailerId  = $request->retailerId;
            }
            
            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
            ->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->get();

            $zone_name_list = [];
            foreach($salesIncentiveReportList as $incentiveList)
            {
                $zoneIdList = json_decode($incentiveList->zone);
                foreach($zoneIdList as $zone) {
                    $zone_name_list[] = DB::table('view_zone_list')
                    ->where('id',$zone)
                    ->where('status',1)
                    ->value('zone_name');
                }
                unset($incentiveList->zone);
            }
            $incentiveList->zone_name = $zone_name_list;
            
            if(isset($salesIncentiveReportList) && !empty($salesIncentiveReportList)) {
                Log::info('Get Sales Incentive List');
                return response()->json($salesIncentiveReportList,200);
            } else {
                Log::warning('Sales Incentive Report Not Found');
                return response()->json(apiResponses(404),404);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function incentiveList(Request $request)
    {
        $authenticateUser = $this->getAuthenticatedUser();
        $headerAuth       = $request->header('Authorization'); 
        $token            = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus     = $this->verifyApiAuth($headerAuth,$token);

        $bpId      = 0;
        $retailId  = 0;
        $groupId   = 0;
        if($request->bpId) {
            $bpId           = $request->bpId;
            $groupId        = 1;
        } else {
            $retailId       = $request->retailerId;
            $groupId        = 2;
        }

        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $incentiveLists = DB::table('incentives')
            ->select('*')
            //->where($authenticateUser['login_field'],$authenticateUser['id'])
            ->where('incentive_group',$groupId)
            ->where('status',1)
            ->get();

            if(isset($incentiveLists) && !empty($incentiveLists)) 
            {
                $incentiveList = [];
                foreach($incentiveLists as $incentive)
                {
                    $getModelId         = json_decode($incentive->product_model);
                    $getIncentiveType   = json_decode($incentive->incentive_type);
                    $getZone            = json_decode($incentive->zone);

                    $ProductModel = DB::table('view_product_master')
                    ->select('product_model')
                    ->whereIn('product_master_id', (array) $getModelId)
                    ->get();

                    $ZoneName = DB::table('zones')
                    ->select('zone_name')
                    ->whereIn('zone_id',(array) $getZone)
                    ->get()
                    ->toArray();

                    $incentive->ProductModel   = $ProductModel;
                    $incentive->IncentiveType  = $getIncentiveType;
                    $incentive->ZoneName       = $ZoneName;

                    unset($incentive->incentive_group,$incentive->product_model,$incentive->incentive_type,$incentive->zone);
                    $incentiveList[] = $incentive;
                }
                
                if(isset($incentiveList) && !empty($incentiveList)) {
                    Log::info('Get Incentive List By Apps Request');
                    return response()->json($incentiveList);
                } else {
                    Log::warning('Incentive List Not Found By Apps Request');
                    return response()->json(apiResponses(404),404);
                }
            }
            else 
            {
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getAuthenticatedUser()
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());

        if($user != null) {
            $id             = $user['bp_id'];
            $login_field    = 'bp_id';

            if ($user['retailer_id'] > 0) {
                $id             = $user['retailer_id'];
                $login_field    = 'retailer_id';
            }

            if ($user['employee_id'] > 0) {
                $id             = $user['employee_id'];
                $login_field    = 'employee_id';
            }
            return ['login_field'=>$login_field,'id'=>$id];
        }
        
    }

    public function verifyBpAttendance($bpId=null,$requestInTime=null,$requestOutTime=null)
    {
        $officeInTime   = strtotime(date("10:00:00"));
        $officeOutTime  = strtotime(date("06:00:00"));
        $checkBpRetailerId = 0;
        if(isset($bpId) && $bpId !=null || $bpId > 0) {
            $checkBpRetailerId = BrandPromoter::where('id','=',$bpId)->value('retailer_id');
            
            if($checkBpRetailerId !=null || $checkBpRetailerId > 0) {
                $response = Retailer::where('id','=',$checkBpRetailerId)
                ->select('shop_start_time','shop_end_time')
                ->first();
                
                if($response['shop_start_time'] != null && $response['shop_end_time'] != null) {
                    
                    $officeInTime  = strtotime(date($response['shop_start_time']));
                    $officeOutTime = strtotime(date($response['shop_end_time']));
                    
                }
            }
            else
            {
                return response()->json(apiResponses(400),400);
            }
            
        }
        
        $getInTime  = round(abs($officeInTime - $requestInTime) / 60,2);
        $getOutTime = round(abs($officeOutTime - $requestOutTime) / 60,2);

        $inStatus   = "";
        $outStatus  = "";


        if($getInTime < 16) {
            $inStatus = "Late In";
        } else {
            $inStatus = "Ok";
        }

        if($getOutTime >16) {
            $outStatus = "Early Out";
        } else {
            $outStatus = "Ok";
        }
        
        
        DB::table('temporaryes')->insert([
            "request_data"=>$bpId.'~'.$inStatus.'~'.$getInTime.'~'.$officeInTime.'~'.$checkBpRetailerId,
            "date"=>date('d-m-Y'),
        ]);
                
        
        $status =["inStatus"=>$inStatus,'outStatus'=>$outStatus];

        return $status;
    }

    public function bpAttendance(Request $request)
    {
        $requestInTime  = strtotime(date("h:i:s"));
        $requestOutTime = strtotime(date("h:i:s"));

        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $SelfiPic = "";
            if($request->hasFile('photo')) {
                // $img = Image::make($image->path());
                // $img->resize(200, 200, function ($constraint) {
                // $constraint->aspectRatio();
                // })->save($destinationPath.'/'.$filename);
                $photo = $request->file('photo');
                $filename = time().'.'.$photo->getClientOriginalExtension();

                $SelfiPic = $filename;
                $destinationPath = public_path('/upload/bpattendance');
                $success = $photo->move($destinationPath, $filename);
            }

            $attendance_date_time = date('Y-m-d H:i:s');
            $location             = $request->input('location');
            $bpId                 = $request->input('bp_id');
            $currentDate          = date('Y-m-d');
            
            
            if(isset($bpId) && $bpId > 0) {
                $CheckAttendance = BpAttendance::
                where('bp_id',$bpId)
                ->where('date','like','%' . $currentDate . '%')
                ->orderBy('date', 'desc')
                ->first();
                /*
                DB::table('temporaryes')->insert([
                    "request_data"=>$jsonFeedArray,
                    "date"=>date('d-m-Y'),
                ]);
                */

                $remarks            = 1;  //First In
                $inStatus           = "";
                $outStatus          = "";
                $responseMessage    = "";
                if($remarks == 1)
                {
                    $response = $this->verifyBpAttendance($bpId,$requestInTime);
                    $inStatus = $response['inStatus'];
                }

                if($CheckAttendance) {
                   $remarkStatus =  $CheckAttendance['remarks'];
                   if($remarkStatus == 1){
                        $remarks = 2; //First Out
    
                        $response = $this->verifyBpAttendance($bpId,0,$requestOutTime);
                        $outStatus = $response['outStatus'];
                        $responseMessage = "Check Out Has Been Successfully";
                   }
                   else if($remarkStatus == 2){
                        $remarks = 3; //Again In
    
                        $response = $this->verifyBpAttendance($bpId,$requestInTime,0);
                        $inStatus = $response['inStatus'];
                        $responseMessage = "Check In Has Been Successfully";
                   }
                   else if($remarkStatus == 3){
                        $remarks = 4; //Again Out
    
                        $response = $this->verifyBpAttendance($bpId,0,$requestOutTime);
                        $outStatus = $response['outStatus'];
                        $responseMessage = "Check Out Has Been Successfully";
                   }
                   else if($remarkStatus == 4){
                        $remarks = 3; //Again In
    
                        $response = $this->verifyBpAttendance($bpId,$requestInTime,0);
                        $inStatus = $response['inStatus'];
                        
                        $responseMessage = "Check In Has Been Successfully";
                   }
                }
                else
                {
                    $responseMessage = "Check In Has Been Successfully";
                }

                $locationDetails= $request->input('location_details');
                $locationDetails=(!empty( $locationDetails))? json_decode($locationDetails,true):[];
                $location='';
                if(!empty($locationDetails) && is_array($locationDetails)){
                    $firstLocation=$locationDetails[0];
                    $subThoroughfare=array_key_exists("subThoroughfare",$firstLocation)?$firstLocation['subThoroughfare']:"";
                    $thoroughfare=array_key_exists("thoroughfare",$firstLocation)?$firstLocation['thoroughfare']:"";
                    $subLocality=array_key_exists("subLocality",$firstLocation)?$firstLocation['subLocality']:"";
                    $locality=array_key_exists("locality",$firstLocation)?$firstLocation['locality']:"";
                    $subAdministrativeArea=array_key_exists("subAdministrativeArea",$firstLocation)?$firstLocation['subAdministrativeArea']:"";
                    $administrativeArea=array_key_exists("administrativeArea",$firstLocation)?$firstLocation['administrativeArea']:"";
                    $fullLocattions=[$subThoroughfare,$thoroughfare, $subLocality,$locality, $subAdministrativeArea,$administrativeArea, $administrativeArea];
                    $fullLocattions=array_filter($fullLocattions);
                    $fullLocattions=array_unique($fullLocattions);
                    $location=join(", ",$fullLocattions);
                    $location.='.';
                }

                $takeAttendance = BpAttendance::create([
                    "bp_id"=> $bpId ? $bpId:0,
                    "location"=> $location,
                    "location_details"=>json_encode($locationDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
                    "selfi_pic"=> $SelfiPic,
                    "date"=> $attendance_date_time,
                    "remarks"=> $remarks ? $remarks : 1,
                    "in_status"=> $inStatus ? $inStatus : "-",
                    "out_status"=> $outStatus ? $outStatus : "-",
                    "status"=> $request->input('status') ? $request->input('status') : "P",
                    "comments"=> $request->input('comments') ? $request->input('comments') : "Good"
                ]);
                
                if($takeAttendance) {
                    Log::info('BP Attendance Got Taken');
                    return response()->json(["message"=>$responseMessage],200);
                } else {
                    Log::warning('BP Attendance Got Taken Failed->OutSourceApiController->bpAttendance');
                    return response()->json(apiResponses(400),400);
                }
            }
            Log::error('Request Invalid BP Id For Attendance ->OutSourceApiController->bpAttendance');
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function bpAttendanceReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $bpId      = 0;
            if($request->bpId) {
                $bpId  = $request->bpId;
            }
            
            $reqSdate       = date($request->startDate);
            $reqEdate       = date($request->endDate);
            if(isset($bpId) && $bpId > 0) {
                $attendanceList = DB::table('view_bp_attendance_report')
                ->where('id',$bpId)
                ->whereBetween(DB::raw('DATE(date_time)'),[$reqSdate,$reqEdate])
                ->get();
    
                if(isset($attendanceList) && $attendanceList->isNotEmpty()) {
                    Log::info('Get Attendance List By Apps');
                    return response()->json($attendanceList,200);
                } else {
                    Log::warning('Attendance List Not Found By Apps Request ->OutSourceApiController->bpAttendanceReport');
                    return response()->json(apiResponses(404),404);
                }
            }
            Log::error('Request Invalid BP Id For Attendance ->OutSourceApiController->bpAttendanceReport');
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }

    }

    public function getLeaveType(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $getAll = DB::table('leave_types')
            ->select('id','name')
            ->where('status',1)
            ->get();

            if(isset($getAll) && !empty($getAll)) {
                return response()->json($getAll,200);
            }
            else {
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getLeaveReason(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $getAll = DB::table('leave_categories')->get();

            if(isset($getAll) && !empty($getAll)) {
                return response()->json($getAll,200);
            }
            else {
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function bpApplyLeave(Request $request)
    {
        
        $jsonData = "";
        $requestInput = $request->input();
        foreach($requestInput as $key=>$allval)
        {
            foreach($allval as $k=>$val) {
                $jsonData = "[".$k."]"; //json_encode($k, JSON_FORCE_OBJECT);//
            }
        }

        $people= json_decode($jsonData, true);
        $inputValue = [];
        for($i=0;$i<count($people); $i++){
            $inputValue[] = $people[$i]["value"];
        }
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            
            $leaveDate = "";
            $leaveTime = "";
            if(isset($inputValue[4]) && !empty($inputValue[4]))
            {
                $str = $inputValue[4];
                $dateArray = explode("T",$str);
                $getDate   = $dateArray[0];
                $timeArray = explode(".",$dateArray[1]);
                $leaveTime   = date("h:i a",strtotime($timeArray[0]));
            }
            
            if(isset($inputValue[2]) && !empty($inputValue[2]))
            {
                $str = $inputValue[2];
                $dateArray = explode("T",$str);
                $getDate   = $dateArray[0];
                $leaveDate = date('Y-m-d',strtotime($getDate));
            }
            
            $apply_date = date('Y-m-d H:i:s');
            $start_date = date('Y-m-d');
            
            $bpId       =   $inputValue[0];
            $leaveType  =   $inputValue[1];
            $startDate  =   $leaveDate ? $leaveDate:date('Y-m-d');
            $totalDay   =   $inputValue[3];
            $startTime  =   $leaveTime ? $leaveTime:date('h:i a');
            $reason     =   $inputValue[5];

            if(isset($bpId) && $bpId > 0) {

                $checkLeave = DB::table('bp_leaves')
                ->where('bp_id',$bpId)
                ->where('leave_type',$leaveType)
                ->where('start_date',$startDate)
                ->where('total_day',$totalDay)
                ->first();

                if($checkLeave)
                {
                    $returnData = [
                        "apply_date"=>$startDate
                    ];
                    return response()->json(['message'=>'Leave All Ready Taken','code'=>203],203);
                }
                else
                {
                    $bpLeave = DB::table('bp_leaves')->insert([
                        "bp_id"=> $bpId ? $bpId:0,
                        "apply_date"=>$apply_date,
                        "leave_type"=> $leaveType ? $leaveType:0,
                        "start_date"=> $startDate,
                        "total_day"=> $totalDay ? $totalDay:0,
                        "start_time"=> $startTime,
                        "reason"=> $reason ? $reason:"",
                        "status"=>"Pending"
                    ]);

                    if(isset($bpLeave) && !empty($bpLeave)) {
                        Log::info('Got BP Leave By Apps Success');
                        return response()->json(['message'=>'success','code'=>200],200);
                    }
                    else {
                        Log::warning('Got BP Leave By Apps Failed');
                        return response()->json(apiResponses(404),404);
                    }
                }
            }
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function bpLeaveReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $bpId      = 0;
            if($request->bpId) {
                $bpId  = $request->bpId;
            } 

            $reqSdate       = $request->startDate;
            $reqEdate       = $request->endDate;

            if(isset($bpId) && $bpId > 0) {
                $leaveList = DB::table('view_bp_leave_report')
                ->orWhere(function($query) use($reqSdate, $reqEdate, $bpId){
                    if ($reqSdate && $reqEdate) {
                        if ($bpId > 0) {
                            $query->whereBetween('start_date',[$reqSdate,$reqEdate]);
                            $query->where('bp_id',$bpId);
                        } else {
                            $query->orWhereBetween('start_date',[$reqSdate,$reqEdate]);
                        }                    
                    }
                })
                ->orderBy('id','desc')
                ->get();

                if(isset($leaveList)) {
                    Log::info('Get BP Leave List By Apps');
                    return response()->json($leaveList,200);
                }
                else {
                    Log::info('BP Leave List Not Found By Apps');
                    return response()->json(apiResponses(404),404);
                }
            }
            Log::error('Request Invalid BP Id For Leave Report ->OutSourceApiController->bpLeaveReport');
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function GetByInfo($methodName,$terms,Request $request)
    {
        $headerAuth = $request->header('Authorization');
        if($headerAuth) {
            if ($tokenFetch = JWTAuth::parseToken()->authenticate()) {
                $token = str_replace("Bearer ", "", $request->header('Authorization'));
                if($token) 
                {
                    if(isset($methodName) && $methodName == 'GetUserById') {

                        $getResult = User::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetRetailerById') {

                        $getResult = Retailer::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetDealerById') {
                        
                        $getResult = DealerInformation::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetEmployeeById') {
                        
                        $getResult = Employee::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetProductById') {
                        
                        $getResult = Products::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetProductByModel') {
                        
                        $getResult = Products::where('product_model','like','%'.$terms.'%');

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetBrandPromoterById') {
                        
                        $getResult = BrandPromoter::find($terms);
                        
                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else 
                    {
                        return response()->json(['error'=>'Data Not Found','status'=>'fail','code'=>404]);
                    }
                }
                else 
                {
                    return response()->json(['error'=>'Unauthorized access','status'=>'fail','code'=>401]);
                }             
            } 
            else 
            {
                return response()->json(['error'=>'token has been expired or revoked','status'=>'fail','code'=>401]);
            }
        } 
        else 
        {
            return response()->json(['error'=>'Unauthorized access','status'=>'fail','code'=>401]);
        }
        //return response()->json($responseArray);
    }
    
    public function GetSalesProduct(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true){

            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');

            $bpId      = 0;
            $retailId  = 0;
            $groupId   = 0;

            if($request->input('bp_id')) {
                $bpId           = $request->input('bp_id');
                $groupId        = 1;

            } else {
                $retailId       = $request->input('retailer_id');
                $groupId        = 2;
            }

            $customer_name  = $request->input('customer_name');
            $customer_phone = $request->input('customer_phone');
            $itemList       = $request->input('list');

            if(is_array($itemList) && !empty($itemList)) {

                $imeProductStatus  = [];
                $imeProductResult  = "";
                $saleStatus        = false;

                $sale_data = "";

                $saleId = "";
                foreach ($itemList as $lists) {
                    $getImeResult = $DelarDistributionModel::
                    where('barcode',$lists['ime_number'])
                    ->orWhere('barcode2',$lists['ime_number'])
                    ->first();

                    if(isset($getImeResult)) 
                    {
                        $productStatus   = $getImeResult['status'];
                        $productMasterId = $getImeResult['product_master_id'];

                        if($productStatus == 1 && $productMasterId > 0) {
                            $imeProductResult = DB::table('view_product_master')
                            ->where('product_master_id',$productMasterId)
                            ->first();
                            
                        

                            if($imeProductResult) {
                                //$imeProductStatus[] = true;
                                if($saleStatus === false) {
                                    
                                    $ClientPic = "";
                                    if($request->hasFile('photo')) {
                                        /*
                                        $image = $request->file('photo');
                                        $filename = time().'.'.$image->getClientOriginalExtension();
                                        $destinationPath = public_path('/upload/client');
                                        $ClientPic = $filename;
                                        /*
                                        $img = Image::make($image->path());
                                        $img->resize(100, 100, function ($constraint) {
                                        $constraint->aspectRatio();
                                        })->save($destinationPath.'/'.$filename);
                                        */
                                        
                                        $getPhoto = $request->file('photo');
                                        $filename = time().'.'.$getPhoto->getClientOriginalExtension();
                                        $destinationPath = public_path('/upload/client');
                                        $success = $getPhoto->move($destinationPath, $filename);
                                        
                                        $ClientPic = $filename;
            
            
                                    }
                                    

                                    Sale::create([
                                        "customer_name"=> $request->input('customer_name'),
                                        "customer_phone"=>  $request->input('customer_phone'),
                                        "bp_id"=> $bpId,
                                        "retailer_id"=> $retailId,
                                        "sale_date"=>date('Y-m-d'),
                                        "photo"=> $ClientPic,
                                        "status"=>0
                                    ]);

                                    $saleId = DB::getPdo()->lastInsertId();

                                    $saleStatus = true;

                                }

                                if(!empty($saleId)) {
                                    SaleProduct::create([
                                        "sales_id"=>$saleId,
                                        "ime_number"=> $lists['ime_number'],
                                        "product_master_id"=> $productMasterId,
                                        "product_id"=> $imeProductResult->product_id,
                                        "product_code"=>  $imeProductResult->product_code,
                                        "product_type"=> $imeProductResult->product_type,
                                        "product_model"=> $imeProductResult->product_model,
                                        "category"=> $imeProductResult->category2,
                                        "mrp_price"=> $imeProductResult->mrp_price,
                                        "msdp_price"=> $imeProductResult->msdp_price,
                                        "msrp_price"=> $imeProductResult->msrp_price,
                                        "sale_price"=> $lists['price'],
                                        "sale_qty"=> $lists['qty'],
                                        "bp_id"=> $bpId,
                                        "retailer_id"=> $retailId,
                                        "product_status"=>0 //Sold Order
                                    ]);
                                    //Ime Database Product Status Update Start
                                    $DelarDistributionModel::
                                    where('barcode',$lists['ime_number'])
                                    ->orWhere('barcode2',$lists['ime_number'])
                                    ->update([
                                        "status"=>0,
                                    ]);
                                }

                                $sale_data = [
                                    "sale_id"=>$saleId,
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "sale_date"=>date('Y-m-d'),
                                    "customer_name"=> $request->input('customer_name'),
                                    "customer_phone"=>  $request->input('customer_phone')
                                ];

                                ///////////////// Incentive Calculation Start ////////////////
                                $saleQty        = $lists['qty'];
                                $saleId         = $saleId;
                                $sale_date      = date('d-m-Y');

                                $incentiveLists = DB::table('incentives')
                                ->where('incentive_group',$groupId)
                                ->get();

                                foreach($incentiveLists as $incentive)
                                {
                                    $insertStatus       = false;
                                    $getModelId         = json_decode($incentive->product_model,TRUE);
                                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                                    $getZone            = json_decode($incentive->zone,TRUE);                                   
                                    if(in_array($productMasterId, $getModelId)) {

                                        $start_date = $incentive->start_date;
                                        $end_date   = $incentive->end_date;
                                        $minQty     = $incentive->min_qty;

                                        $totalSaleQty = DB::table('view_sales_reports')
                                        ->where('product_master_id',$productMasterId)
                                        ->whereBetween('sale_date',[$start_date,$end_date])
                                        ->sum('view_sales_reports.sale_qty');

                                        if($totalSaleQty >= $minQty)
                                        {
                                            $bpId = 0;
                                            $retailer_id = 0;
                                            
                                            if($groupId == 1 && in_array('bp', $getIncentiveType)){
                                                $bpId = $bpId;
                                                $retailer_id = 0;
                                                $insertStatus = true;
                                            }
                                            else if($groupId == 2 && in_array($retailer_id, $getIncentiveType))
                                            {
                                                $bpId = 0;
                                                $retailer_id = $retailId;
                                                $insertStatus = true;
                                            }
                                            

                                            if($insertStatus === true)
                                            {
                                                $insertData = array(
                                                    "ime_number"=>$lists['ime_number'],
                                                    "sale_id" =>$saleId, 
                                                    "bp_id" =>$bpId,
                                                    "retailer_id"=>$retailId,
                                                    "incentive_for"=>$retailId,
                                                    "incentive_title"=>$incentive->incentive_title,
                                                    "product_model"=>$imeProductResult->product_model,
                                                    "zone"=>$incentive->zone,
                                                    "incentive_amount"=>$incentive->incentive_amount,
                                                    "incentive_min_qty"=>$incentive->min_qty,
                                                    "incentive_sale_qty"=>$saleQty,
                                                    "start_date"=>$incentive->start_date,
                                                    "end_date"=>$incentive->end_date,
                                                    "incentive_status"=>$incentive->status,
                                                );

                                            }
                                        }

                                    }
                                }
                                //////////////////// Incentive Calculation End //////////////////
                            }
                            else
                            {

                                $saleRemove = DB::table('sales')
                                ->where('id',$saleId)
                                ->delete();

                                $saleItemsRemove = DB::table('sale_products')
                                ->where('sales_id',$saleId)
                                ->delete();

                                //Ime Database Product Status Update Start
                                $DelarDistributionModel::
                                where('barcode',$lists['ime_number'])
                                ->orWhere('barcode2',$lists['ime_number'])
                                ->update([
                                    "status"=>1,
                                ]);

                                $notFoundIme[] = $lists['ime_number'];
                                //$response = apiResponses(301,$notFoundIme);//Data Not Found

                                return response()->json(["message"=> "Ime Not Found.Please Contact Your Authority","not_found_ime"=>$notFoundIme,"code"=>404],404);
                            }
                        }
                        else 
                        {
                            return response()->json(apiResponses(422,'Product All Ready Sold'),422);
                        }
                    }
                    else
                    {
                        return response()->json(apiResponses(422,'Invalid Ime Number'),422);
                    }

                }

                if(isset($sale_data) && !empty($sale_data))
                {
                    return response()->json($sale_data,200);//Success
                }
                
            }
            else {
                Log::warning('Bad Request Get Sales Product ->OutSourceApiController->GetSalesProduct');
                return response()->json(apiResponses(400),400);//Bad Request
            }
         
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getProductList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $productList['smartPhoneList'] = DB::table('view_product_master')
            ->select('product_model as name','product_model as model','product_code as code','product_type as type','msrp_price as price','category2 as group')
            ->where('category2','Smart')
            ->get();


            $productList['featurePhoneList'] = DB::table('view_product_master')
             ->select('product_model as name','product_model as model','product_code as code','product_type as type','msrp_price as price','category2 as group')
            ->where('category2','Feature')
            ->get();


            if(isset($productList) && !empty($productList)) {
                Log::info('Get Product List By Apps');
                return response()->json($productList,200);
            }
            else {
                Log::warning('Product List Not Found->OutSourceApiController->getProductList');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPromoOffer_02_09_2021(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            
            $bpId      = 0;
            $retailId  = 0;

            $zoneName  = "";

            if($request->bpId) {
                $bpId       = $request->bpId;
            } else {
                $retailId   = $request->retailId;
                $zoneId     = DB::table('retailers')->where('retailer_id',$retailId)->value('zone_id');
                $zoneName   = DB::table('zones')->where('zone_id',$zoneId)->value('zone_name');
            }

            $promoOfferList = DB::table('promo_offers')->get();

            foreach($promoOfferList as $row)
            {
                if(!empty($row->zone)) {
                    foreach(json_decode($row->zone, true) as $key => $value) {
                        if($value == $zoneName)
                        {
                            $offerInfo = DB::table('promo_offers')
                            ->select('title','sdate','edate','offer_pic')
                            ->where('id',$row->id)
                            ->where('status',1)
                            ->orderBy('id','desc')
                            ->first();

                            $zoneInfo = DB::table('promo_offers')
                            ->select('zone')
                            ->where('id',$row->id)
                            ->first();

                            $zoneList =[];
                            foreach(json_decode($zoneInfo->zone, true) as $key => $value){
                                $zoneList[] = $value;
                            }
                            $offerInfo->zoneList = $zoneList;
                            return response()->json($offerInfo,200);
                        }
                    }
                }
                else
                {
                    $getOfferDate   = DB::table('promo_offers')->where('status',1)->orderBy('id','desc')->first();

                    $start_date     = strtotime($getOfferDate->sdate);
                    $end_date       = strtotime($getOfferDate->edate);
                    $current_date   = strtotime(date('Y-m-d'));
                    
                    $month_Sdate    =  date('Y-m-01');
                    $month_Edate    =  date('Y-m-t');


                    if (($current_date >= $start_date) && ($current_date <= $end_date)) 
                    {
                        $offerInfo = DB::table('promo_offers')
                        ->select('title','sdate','edate','offer_pic')
                        ->where('status',1)
                        ->whereBetween('sdate',[$month_Sdate,$month_Edate])
                        ->orderBy('id','desc')
                        ->get();

                        $zoneInfo = DB::table('promo_offers')
                        ->select('zone')
                        ->first();

                        $zoneList =[];
                        if(!empty($zoneInfo->zone)) {
                            foreach(json_decode($zoneInfo->zone, true) as $key => $value) {
                                $zoneList[] = $value;
                            }
                        }
                        $offerInfo->zoneList = $zoneList;
                        return response()->json($offerInfo,200);
                    }else{    
                    return response()->json([],200); 
                    }
                }
                /*
                foreach(json_decode($row->zone, true) as $key => $value)
                {
                    if($value == $zoneName)
                    {
                        $offerInfo = DB::table('promo_offers')
                        ->select('title','sdate','edate','offer_pic')
                        ->where('id',$row->id)
                        ->where('status',1)
                        ->orderBy('id','desc')
                        ->first();

                        $zoneInfo = DB::table('promo_offers')
                        ->select('zone')
                        ->where('id',$row->id)
                        ->first();

                        $zoneList =[];
                        foreach(json_decode($zoneInfo->zone, true) as $key => $value){
                            $zoneList[] = $value;
                        }
                        $offerInfo->zoneList = $zoneList;
                        return response()->json($offerInfo,200);
                    }
                    else
                    {
                        $getOfferDate   = DB::table('promo_offers')->where('status',1)->orderBy('id','desc')->first();

                        $start_date     = strtotime($getOfferDate->sdate);
                        $end_date       = strtotime($getOfferDate->edate);
                        $current_date   = strtotime(date('Y-m-d'));
                        
                        $month_Sdate    =  date('Y-m-01');
                        $month_Edate    =  date('Y-m-t');


                        if (($current_date >= $start_date) && ($current_date <= $end_date)) {
                            $offerInfo = DB::table('promo_offers')
                            ->select('title','sdate','edate','offer_pic')
                            ->where('status',1)
                            ->whereBetween('sdate',[$month_Sdate,$month_Edate])
                            ->get();

                            $zoneInfo = DB::table('promo_offers')
                            ->select('zone')
                            ->first();

                            $zoneList =[];
                            foreach(json_decode($zoneInfo->zone, true) as $key => $value) {
                                $zoneList[] = $value;
                            }
                            $offerInfo->zoneList = $zoneList;
                            return response()->json($offerInfo,200);
                        }else{    
                        return response()->json([],200); 
                        }
                    }
                }
                */
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPromoOffer(Request $request) 
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $month_Sdate    =  date('Y-m-01');
            $month_Edate    =  date('Y-m-t');

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bpId      = 0;
            $retailId  = 0;
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
            }

            if($bpId && $bpId > 0) {

                $promoOfferList = DB::table('promo_offers')
                ->select('title','offer_for','sdate','edate','offer_pic')
                ->where('offer_for','=','all')
                ->where('status',1)
                ->orWhere('offer_for','=','bp')
                ->orWhereBetween('sdate',[$month_Sdate,$month_Edate])
                ->get();
                
                return response()->json($promoOfferList,200);
            } else {

                $promoOfferList = DB::table('promo_offers')
                ->select('title','offer_for','sdate','edate','offer_pic')
                ->where('offer_for','=','all')
                ->where('status',1)
                ->orWhere('offer_for','=','retailer')
                ->OrWhereBetween('sdate',[$month_Sdate,$month_Edate])
                ->get();
                
                return response()->json($promoOfferList,200);
            }
            return response()->json(apiResponses(404),404);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function messageStore(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();
    
            $phone = "";
            $zone  = "";
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpInfo = DB::table('brand_promoters')
                ->where('id',$userExists->bp_id)
                ->first();

                $dealerCode     = $bpInfo->distributor_code;
                $alternetCode   = $bpInfo->distributor_code2;

                $dealerZoneName = DB::table('dealer_informations')
                ->where('dealer_code',$dealerCode)
                ->where('alternate_code',$alternetCode)
                ->value('zone');

                $phone   = $bpInfo->bp_phone;
                $zone    = $dealerZoneName;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailerInfo = DB::table('retailers')
                ->where('retailer_id',$userExists->retailer_id)
                ->first();

                $RetailerZone= DB::table('zones')
                ->where('id','=',$retailerInfo->zone_id)
                ->value('zone_name');

                $phone   = $retailerInfo->phone_number;
                $zone    = $RetailerZone;
            }
            elseif($userExists->employee_id > 0 && $userExists->employee_id != NULL) {
                $employeeInfo = DB::table('employees')
                ->where('employee_id',$userExists->employee_id)
                ->first();

                $phone   = $employeeInfo->mobile_number;
                $zone    = "";
            }

            $messageId      = $request->input('message_id');
        
            $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();

            $messageStatus  = 1;
            if(isset($CheckStatus) && $CheckStatus['who_reply'] == $userId && $CheckStatus['id'] == $messageId) {
                $messageStatus = 0;
            }

            if($CheckStatus) {
                if($CheckStatus['bnm'] == 2) {
                    $updateBnmStatus = AuthorityMessage::where('id',$messageId)
                    ->update([
                        "bnm"=>1
                    ]); 
                }
                $AddMessage = AuthorityMessage::create([
                    "message"=>$request->input('message'),
                    "date_time"=>date('Y-m-d H:i:s'),
                    "bnm"=>0,
                    "status"=>$messageStatus,
                    'reply_for'=> $messageId ? $messageId:0,
                    'who_reply'=> $userId ? $userId:0,
                    "reply_user_name"=>$userExists->name,
                    "phone"=>$phone,
                    "zone"=>$zone
                ]);
                Log::info('Authority Message Save Success');
                return response()->json(['message'=>'success','code'=>200],200);
            } 
            else 
            {
                $AddMessage = AuthorityMessage::create([
                    "message"=>$request->input('message'),
                    "date_time"=>date('Y-m-d H:i:s'),
                    "bnm"=>2,
                    "status"=>0,
                    'reply_for'=>0,
                    'who_reply'=>$userId ? $userId:0,
                    "reply_user_name"=>$userExists->name,
                    "phone"=>$phone,
                    "zone"=>$zone
                ]);
                
                $lastInsertId = DB::getPdo()->lastInsertId();
                $updateBnmStatus = AuthorityMessage::where('id',$lastInsertId)
                ->update([
                    "reply_for"=>$lastInsertId
                ]);
                
                Log::info('Authority Message Save Success');
                return response()->json(['message'=>'success','code'=>200],200);
            }
        }
        return response()->json(apiResponses(404),404);
    }

    public function replyMessage(Request $request)
    {
        
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            /*
            $bpId      = 0;
            $retailId  = 0;

            if($request->bpId) {
                $bpId       = $request->bpId;
            } else {
                $retailId   = $request->retailId;
            }
            */
            //////////////////////////////////////
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            
            $phone = "";
            $zone  = "";
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpInfo = DB::table('brand_promoters')
                ->where('id',$userExists->bp_id)
                ->first();

                $dealerCode     = $bpInfo->distributor_code;
                $alternetCode   = $bpInfo->distributor_code2;

                $dealerZoneName = DB::table('dealer_informations')
                ->where('dealer_code',$dealerCode)
                ->where('alternate_code',$alternetCode)
                ->value('zone');

                $phone   = $bpInfo->bp_phone;
                $zone    = $dealerZoneName;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailerInfo = DB::table('retailers')
                ->where('retailer_id',$userExists->retailer_id)
                ->first();

                $RetailerZone= DB::table('zones')
                ->where('id','=',$retailerInfo->zone_id)
                ->value('zone_name');

                $phone   = $retailerInfo->phone_number;
                $zone    = $RetailerZone;
            }
            elseif($userExists->employee_id > 0 && $userExists->employee_id != NULL) {
                $employeeInfo = DB::table('employees')
                ->where('id',$userExists->employee_id)
                ->first();

                $phone   = $employeeInfo->mobile_number;
                $zone    = "Others";
            }

            $messageId      = $request->input('message_id');

            $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();

            if(isset($CheckStatus))
            {
                if($CheckStatus['bnm'] == 2) {
                    AuthorityMessage::where('id',$messageId)
                        ->update([
                            "bnm"=>1
                        ]);
                }
                
                $AddMessage = AuthorityMessage::create([
                    "message"=>$request->input('message'),
                    "date_time"=>date('Y-m-d H:i:s'),
                    "bnm"=>0,
                    "status"=>1,
                    'reply_for'=>$messageId,
                    //'who_reply'=>$request->input('retailer_id')
                    'who_reply'=>$userId ? $userId:0,
                    "reply_user_name"=>$userExists->name,
                    "phone"=>$phone ? $phone:0,
                    "zone"=>$zone ? $zone:0
                ]);
                Log::info('Authority Message Reply Success');
                return response()->json(['message'=>'success','code'=>200],200);
            }
            Log::warning('Invalid Authority Message');
            return response()->json(apiResponses(401),401);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getMessageList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $messageId   = $request->messageId;

            $responseMessage = [];

            /*$AuthorFirstMessage = [
                "id"=>3,
                "message"=>"Chcekc Message",
                "author"=>"Sayed",
            ];*/

            $FirstMessage = DB::table('authority_messages')
            ->select('id','message','date_time','reply_user_name','who_reply')
            ->where('id', $messageId)
            ->where('status', 0)
            ->orderBy('id','asc')
            ->first();

            $AuthorFirstMessage = [
                "id"=>$FirstMessage->id,
                "message"=>$FirstMessage->message,
                "dateTime"=>$FirstMessage->date_time,
                "isRead"=>true,
                "isSent"=>true,
                "author"=>[
                   "id"=>$FirstMessage->who_reply,
                   "name"=>$FirstMessage->reply_user_name
                ],
            ];

            $MessageList = DB::table('authority_messages')
            ->select('message','date_time','status','reply_user_name','who_reply')
            ->where('reply_for', $messageId)
            ->where('bnm','=',0)
            ->orderBy('id','asc')
            ->get();

            $replyMessage = [];
            foreach($MessageList as $row)
            {
                $newMessage=[
                    "message"=>$row->message,
                    "dateTime"=> $row->date_time,
                    "isRead"=>true,
                    "isSent"=>true,
                    "author"=>[
                    "id"=>$row->who_reply,
                    "name"=>$row->reply_user_name
                ]];
                array_push($replyMessage, $newMessage);
            }

            $responseMessage = $AuthorFirstMessage;
            $responseMessage['replies'] = $replyMessage;
            
            if($responseMessage) {
                Log::info('Get Authority Message List');
                return response()->json($responseMessage,200);
            } else {
                Log::warning('Authority Message List Not Found');
                return response()->json($responseMessage,200);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getMessageListByUserId(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id; //exit(); //1 
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $last_msg_list = DB::table('authority_messages as tab1')
                ->select('tab1.*')
                ->leftJoin('authority_messages as tab2',function($join_query) {
                    $join_query->on('tab2.reply_for','=','tab1.reply_for');
                    $join_query->on('tab2.id','>','tab1.id');
                })
                ->whereNull('tab2.id')
                ->where('tab1.bnm','=',0)
                ->where('tab1.reply_for','=',\DB::raw("(SELECT reply_for FROM authority_messages WHERE who_reply=".$userId." AND reply_for=tab1.reply_for ORDER BY id DESC LIMIT 1)"))
                ->groupBy('tab1.who_reply','tab1.id');
                //->orderBy('tab1.id','desc');
                
            

            $UserMessageList = DB::table('authority_messages')
                ->select('*')
                ->where('bnm','=',2)
                ->where('who_reply','=',$userId)
                ->union($last_msg_list)
                ->orderBy('id','desc')
                ->get();

            $responseMessage = [];
            foreach($UserMessageList as $message)
            {
                $responseMessage[] = [
                    "id"=>$message->reply_for,
                    "message"=>$message->message,
                    "dateTime"=>$message->date_time,
                    "author"=>[
                       "id"=>$message->who_reply,
                       "name"=>$message->reply_user_name,
                       "phone"=>$message->phone ? $message->phone:"",
                       "zone"=>$message->zone ? $message->zone:""
                    ],
                ];
            }

            if($responseMessage) {
                Log::info('Get Message By User Id');
                return response()->json($responseMessage,200);
            } else {
                Log::warning('Message Not Found By User Id');
                return response()->json(apiResponses(404),404);
            }

        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getMessageListByUserId_old(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id; //exit(); //1 
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            /*
            $UserMessageList = DB::table('authority_messages')
            ->select('id','message','date_time','reply_user_name','who_reply','phone','zone')
            ->where('who_reply', $userId)
            ->where('reply_for', 0)
            ->orderBy('id','desc')
            ->get();
            */
            $last_msg_list = DB::table('authority_messages as tab1')
                ->select('tab1.*')
                ->leftJoin('authority_messages as tab2',function($join_query) {
                    $join_query->on('tab2.reply_for','=','tab1.reply_for');
                    $join_query->on('tab2.id','>','tab1.id');
                })
                ->whereNull('tab2.id')
                ->where('tab1.bnm','=',0)
                ->where('tab1.reply_for','=',\DB::raw("(SELECT reply_for FROM authority_messages WHERE who_reply=".$userId." AND reply_for = tab1.reply_for ORDER BY id DESC LIMIT 1)"))
                ->groupBy('tab1.who_reply','tab1.id');
                //->orderBy('tab1.id','desc');
                
            

            $UserMessageList = DB::table('authority_messages')
                ->select('*')
                ->where('bnm','=',2)
                ->where('who_reply','=',$userId)
                ->union($last_msg_list)
                ->orderBy('id','desc')
                ->get();
                
                
            //return response()->json($UserMessageList);
            
            // $UserMessageList = DB::table('authority_messages as tab1')
            // ->select('tab1.*')
            // ->leftJoin('authority_messages as tab2','tab2.id','=','tab1.id')
            // ->where('tab1.who_reply', $userId)
            // ->where('tab2.id','=',DB::raw("(SELECT `id` FROM `authority_messages` WHERE `reply_for` = `tab2`.`reply_for` ORDER BY `id` DESC LIMIT 1)"))
            // ->groupBy('tab2.reply_for')
            // ->orderBy('tab1.id','desc')
            // ->get();

            $responseMessage = [];
            foreach($UserMessageList as $message)
            {
                $responseMessage[] = [
                    "id"=>$message->reply_for,
                    "message"=>$message->message,
                    "dateTime"=>$message->date_time,
                    "author"=>[
                       "id"=>$message->who_reply,
                       "name"=>$message->reply_user_name,
                       "phone"=>$message->phone ? $message->phone:"",
                       "zone"=>$message->zone ? $message->zone:""
                    ],
                ];
            }

            if($responseMessage) {
                return response()->json($responseMessage,200);
            } else {
                return response()->json(apiResponses(404),404);
            }

        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function userProfileUpdate(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $userId             = auth('api')->user()->id;
            $password           = $request->input('password');
            $confirm_password   = $request->input('confirm_password');

            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $updatePassword = $userExists->password;
            if($password === $confirm_password) {
                $updatePassword = Hash::make($password);

                if ($userExists) {
                    $UpdateUser = DB::table('users')
                    ->where('id',$userExists->id)
                    ->update([
                        "password"=>$updatePassword
                    ]);
                    Log::info('User Profile Update Success By Apps');
                    return response()->json(['message'=>'success','code'=>200],200);
                }
            }
            Log::error('Password & Confirme Password Not Match By Apps');
            return response()->json(apiResponses(401,'Password & Confirme Password Not Match'),401);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }

    }

    public function getBannerList_old(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bpId      = 0;
            $retailId  = 0;
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
            }
            
            if($bpId && $bpId > 0) {
                $bannerList    = DB::table('banners')
                ->select('banner_pic as photo','image_path')
                ->where('offer_for','=','all')
                ->where('status',1)
                ->orWhere('offer_for','=','bp')
                ->orderBy('id','desc')
                ->get();
                return response()->json($bannerList,200);
            } else {
                
                $bannerList    = DB::table('banners')
                ->select('banner_pic as photo','image_path')
                ->where('offer_for','=','all')
                ->where('status',1)
                ->orWhere('offer_for','=','retailer')
                ->orderBy('id','desc')
                ->get();
                
                Log::info('Get Banner List By Apps');
                return response()->json($bannerList,200);
            }
            Log::warning('Banner List Not Found By Apps');
            return response()->json(apiResponses(404),404);
        } 
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getBannerList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {

            $getBannerList    = DB::table('banners')
            ->select('banner_pic as photo','image_path')
            ->where('status',1)
            ->orderBy('id','desc')
            ->get();
            
            $bannerList = [];
            $baseUrl = URL::to('');
            if(isset($getBannerList) && $getBannerList->isNotEmpty()){
                foreach($getBannerList as $k=>$row){
                    $bannerList[$k]['photo'] = $row->photo;
                    $bannerList[$k]['image_path'] = $baseUrl.'/'.$row->image_path;
                }
            }

            if($bannerList) {
                Log::info('Get Banner List By Apps');
                return response()->json($bannerList,200);
            } else {
                Log::warning('Banner List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        } 
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function ModelWaiseSalesReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;
            if($request->bpId) {
                $bpId           = $request->bpId;
            } else {
                $retailId       = $request->retailId;
            }

            $reqSdate       = $request->startDate;
            $reqEdate       = $request->endDate;

            $responseArray = [];

            $productModelSalesList = DB::table('view_sales_reports')
            //->select('id','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
            ->select('id','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price')
            ->selectRaw('count(sale_qty) as saleQty')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->whereBetween('sale_date',[$reqSdate,$reqEdate])
            ->groupBy('product_model')
            ->orderBy('id','asc')
            ->get();

            $productColorSalesList = DB::table('view_sales_reports')
            ->select('id','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price')
            ->selectRaw('count(sale_qty) as saleQty')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->whereBetween('sale_date',[$reqSdate,$reqEdate])
            ->groupBy('product_color')
            ->orderBy('id','asc')
            ->get();

            $salerInfo = "";

            if($bpId > 0) {
                $salerInfo = DB::table('brand_promoters')
                ->select('bp_name','bp_phone')
                ->where('id',$bpId)
                ->get();
            }
            else {
                $salerInfo = DB::table('retailers')
                ->select('retailer_name','retailder_address','phone_number')
                ->where('id',$retailId)
                ->get();
            }

            foreach($salerInfo as $saler)
            {
                $saler->salesModelInfo = $productModelSalesList;
                $saler->salesProductColorInfo = $productColorSalesList;
            }

            if(count($salerInfo) > 0) {
                Log::info('Get Model Waise Sales List');
                return response()->json($salerInfo,200);
            } else {
                Log::warning('Model Waise Sales List Not Found');
                return response()->json(apiResponses(404),404);
            }

        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getRetailerStock_1(Request $request)
    {
        
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $retailId          = $request->retailId;
            
            if(isset($retailId) && $retailId > 0) {
                $retailerInfo  = DB::table('retailers')
                ->select('retailer_name','retailder_address','phone_number','distributor_code','distributor_code2')
                ->where('id',$retailId)
                ->first();
    
                $phone              = $retailerInfo->phone_number; //$request->PhoneNumber; //"01796452391";
                $dCode              = $retailerInfo->distributor_code ? $retailerInfo->distributor_code : $retailerInfo->distributor_code2; //$request->DealerCode; //"58133"; //Dealer Code

                $dealerInfo = DB::table('dealer_informations')
                ->select('dealer_name','dealer_address','zone','dealer_phone_number')
                ->where('dealer_code',$dCode)
                ->orWhere('alternate_code',$dCode)
                ->get();

                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerStock"),$phone,$dCode),"GET");
                $responseData       = json_decode($getCurlResponse['response_data'],true);
            
                if(isset($responseData)) {
                    foreach($dealerInfo as $row) {
                        $row->stockList = $responseData;
                    }
                    if(count($responseData) > 0) {
                        return response()->json($dealerInfo,200);
                    } else {
                        Log::warning('Retailer Stock Not Found');
                        return response()->json(apiResponses(404),404);
                    }
                }
                else
                {
                    Log::warning('Retailer Stock Not Found');
                    return response()->json(apiResponses(404),404);exit();
                }
            }
            else
            {
                return response()->json(apiResponses(406),406);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getRetailerStock(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bpId       = 0;
            $retailId   = 0;
            $clientType = "";
            $clientInfo = "";
            $searchId   = 0;
            $FocusModel = "";
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId       = $userExists->bp_id;
                $clientInfo = DB::table('brand_promoters')->where('id','=',$bpId)->first();
                $clientType = "dealer";
                $searchId   = $clientInfo->bp_phone;
                $FocusModel = DB::table('bp_model_stocks')->where('bp_category_id','=',$clientInfo->category_id)->get();
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId   = $userExists->retailer_id;
                $clientInfo = DB::table('retailers')->where('id','=',$retailId)->first();
                $clientType = "retailer";
                $searchId   = $clientInfo->phone_number;
                $FocusModel = DB::table('bp_model_stocks')->where('bp_category_id','=',$clientInfo->category_id)->get();
            }
            
            $responseData = "";
            /*
            if(isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {
                $getCurlResponse    = curlAPI(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
                $responseData       = json_decode($getCurlResponse,true);
            }
            */
            
            if(isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {
                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
                $responseData       = json_decode($getCurlResponse['response_data'],true);
            }
            //return response()->json($responseData,200);
            
            $getStockList = [];
            $userInfo     = "";

            if(isset($responseData) && !empty($responseData))
            {
                $userInfo = [
                    "dealer_name"=>$responseData[0]['DealerName'],
                    "dealer_address"=>$responseData[0]['RetailerAddress'],
                    "zone"=>$responseData[0]['DealerZone'],
                    "dealer_phone_number"=>$responseData[0]['DealerPhone']
                ];
                
                $modelList = DB::table('bp_model_stocks')
                ->select('id','bp_category_id','model_name','green','yellow','red')
                ->get();
                
                foreach($modelList as $key=>$row) {
                    $getModelStatus     =  array_search($row->model_name, array_column($responseData, 'Model'));
                    $getModelStockQty   =  array_search($row->green, array_column($responseData, 'StockQuantity'));

                    if(isset($getModelStatus) && !empty($getModelStatus)) 
                    {
                        $statusColor    = "#ff0000";//"Red";
                        if($getModelStockQty >= $row->green) {
                            $statusColor = "#008000";//"Green";
                        } elseif($getModelStockQty >= $row->yellow && $getModelStockQty < $row->green) {
                            $statusColor = "#FFFF00";//"Yellow";
                        } elseif($getModelStockQty >= $row->red) {
                            $statusColor = "#ff0000";//"Red";
                        }
                        
                        $getStockList[$key]['Model'] = $row->model_name;
                        $getStockList[$key]['Color'] = "";
                        $getStockList[$key]['Stock'] = $getModelStockQty;
                        $getStockList[$key]['ColorCode'] = $statusColor;
                    }
                    else
                    {
                        $statusColor    = "#ff0000";//"Red";
                        $stock          = 0;
                        if($getModelStockQty >= 2) {
                            $statusColor = "#008000";//"Green";
                            $stock =2;
                        } elseif($getModelStockQty >= 1 && $getModelStockQty < 2) {
                            $statusColor = "#FFFF00";//"Yellow";
                            $stock =1;
                        } elseif($getModelStockQty >= 0) {
                            $statusColor = "#ff0000";//"Red";
                            $stock =0;
                        }
                        
                        $getStockList[$key]['Model'] = $row->model_name;
                        $getStockList[$key]['Color'] = "";
                        $getStockList[$key]['Stock'] = $stock;
                        $getStockList[$key]['ColorCode'] = $statusColor;
                    }
                    
                }
            }
        
            if(isset($responseData)) {
                $dealerInfo['stockList'] = $getStockList;
                return response()->json($getStockList,200);
            } else {
                Log::warning('Stock Not Found');
                return response()->json(apiResponses(404),404);exit();
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getRetailerStock_2(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bpId       = 0;
            $retailId   = 0;
            $clientType = "";
            $clientInfo = "";
            $searchId   = 0;
            $FocusModel = "";
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId       = $userExists->bp_id;
                $clientInfo = DB::table('brand_promoters')->where('id','=',$bpId)->first();
                $clientType = "dealer";
                $searchId   = $clientInfo->bp_phone;
                $FocusModel = DB::table('bp_model_stocks')->where('bp_category_id','=',$clientInfo->category_id)->limit(10)->get();
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId   = $userExists->retailer_id;
                $clientInfo = DB::table('retailers')->where('id','=',$retailId)->first();
                $clientType = "retailer";
                $searchId   = $clientInfo->phone_number;
                $FocusModel = DB::table('bp_model_stocks')->where('bp_category_id','=',$clientInfo->category_id)->limit(10)->get();
            }
            
            $responseData = "";
            if(isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {
                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
                $responseData       = json_decode($getCurlResponse['response_data'],true);
            }
            
            $getStockList = [];
            $userInfo     = "";
            if(isset($responseData) && !empty($responseData)){
                $userInfo = [
                    "dealer_name"=>$responseData[0]['DealerName'],
                    "dealer_address"=>$responseData[0]['RetailerAddress'],
                    "zone"=>$responseData[0]['DealerZone'],
                    "dealer_phone_number"=>$responseData[0]['DealerPhone']
                ];

                foreach($responseData as $key=>$row) {
                   $getStockInfo = checkBPFocusModelStock($row['Model']);
                    if(isset($getStockInfo) && !empty($getStockInfo)) 
                    {
                        $statusColor = "#ff0000";//"Red";
                        if($row['StockQuantity'] >= $getStockInfo->green) {
                            $statusColor = "#008000";//"Green";
                        } elseif($row['StockQuantity'] >= $getStockInfo->yellow && $row['StockQuantity'] < $getStockInfo->green) {
                            $statusColor = "#FFFF00"; //"Yellow";
                        } elseif($row['StockQuantity'] >= $getStockInfo->red) {
                            $statusColor = "#ff0000";//"Red";
                        }
                        
                        $getStockList[$key]['Model'] = $row['Model'];
                        $getStockList[$key]['Color'] = "";
                        $getStockList[$key]['Stock'] = $row['StockQuantity'];
                        $getStockList[$key]['ColorCode'] = $statusColor;
                    }
                    else
                    {
                        $statusColor = "#ff0000";//"Red";
                        if($row['StockQuantity'] >= 2) {
                            $statusColor = "#008000";//"Green";
                        } elseif($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
                            $statusColor = "#FFFF00"; //"Yellow";
                        } elseif($row['StockQuantity'] <= 0) {
                            $statusColor = "#ff0000";//"Red";
                        }
                        
                        $getStockList[$key]['Model'] = $row['Model'];
                        $getStockList[$key]['Color'] = "";
                        $getStockList[$key]['Stock'] = $row['StockQuantity'];
                        $getStockList[$key]['ColorCode'] = $statusColor;//#ff0000/"Red";
                    }
                }
            }
            
            if(isset($responseData)) {
                $dealerInfo['stockList'] = $getStockList;
                return response()->json($getStockList,200);
            } else {
                Log::warning('Stock Not Found');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getTopSellerList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            
            //Current Month Top Seller Searching
            $start_date       =  date('Y-m-01');
            $current_date     =  date('Y-m-d');

            $bpTopList = DB::table('view_sales_reports')
            ->select('bp_name as name','bp_phone as phone','bp_distric as distric',DB::raw('SUM(sale_qty) AS totalQty'),DB::raw('SUM(sale_price) AS totalPrice'))
            //->whereBetween('sale_date',[$start_date,$current_date])
            ->whereBetween(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$start_date,$current_date])
            ->where('bp_id','>',0)
            ->groupBy('bp_id')
            ->orderBy('totalQty','desc')
            ->orderBy('totalPrice','desc')
            ->get();

            $retailerTopList = DB::table('view_sales_reports')
            ->select('retailer_name as name','retailer_phone_number as phone','retailer_distric as distric',DB::raw('SUM(sale_qty) AS totalQty'),DB::raw('SUM(sale_price) AS totalPrice'))
            //->whereBetween('sale_date',[$start_date,$current_date])
            ->whereBetween(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$start_date,$current_date])
            ->where('retailer_id','>',0)
            ->groupBy('retailer_id')
            ->orderBy('totalQty','desc')
            ->orderBy('totalPrice','desc')
            ->get();
            $responseArray['bpTopList']         = $bpTopList;
            $responseArray['retailerTopList']   =  $retailerTopList;
            
            if($responseArray) {
                Log::info('Get Top Seller List By Apps');
                return response()->json($responseArray,200);
            } else {
                Log::warning('Top Seller List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function old_postIMEIdisputeNumber(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;

            if($request->input('bp_id')) {
                $bpId       = $request->input('bp_id');
            } else {
                $retailId   = $request->input('retailer_id');
            }
            $imeNumber      = $request->input('imei_number');
            $description    = $request->input('description');

            $CheckStatus    = DB::table('imei_disputes')
            ->where('imei_number',$imeNumber)
            ->first();

            if($CheckStatus) 
            {
                $updateInfo = DB::table('imei_disputes')
                ->where('imei_number',$imeNumber)
                ->update([
                    "bp_id"=>$bpId,
                    "retailer_id"=>$retailId,
                    "imei_number"=>$imeNumber,
                    "description"=>$description,
                    "date"=>date('Y-m-d'),
                    'status'=>0, //0 =no reply,1 reply
                ]);
            }
            else
            {
                $AddInfo = DB::table('imei_disputes')
                ->insert([
                    "bp_id"=>$bpId,
                    "retailer_id"=>$retailId,
                    "imei_number"=>$imeNumber,
                    "description"=>$description,
                    "date"=>date('Y-m-d'),
                    'status'=>0, //0 =no reply,1 reply
                ]);
            }
            return response()->json(['message'=>'success','code'=>200],200);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function postIMEIdisputeNumber(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;

            if($request->input('bp_id')) {
                $bpId       = $request->input('bp_id');
            } else {
                $retailId   = $request->input('retailer_id');
            }
            $imeNumber      = $request->input('imei_number');
            $description    = $request->input('description');

            $CheckStatus    = DB::table('imei_disputes')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->where('imei_number',$imeNumber)
            ->first();

            $message = "";

            if($CheckStatus) 
            {
                $message = "Request All Ready Send";
            }
            else
            {
                $AddInfo = DB::table('imei_disputes')
                ->insert([
                    "bp_id"=>$bpId,
                    "retailer_id"=>$retailId,
                    "imei_number"=>$imeNumber,
                    "description"=>$description,
                    "date"=>date('Y-m-d'),
                    'status'=>0, //0 =no reply,1 reply
                ]);
                $message = "success";
            }
            Log::info('IMEI Dispute Request Success');
            return response()->json(['message'=>$message,'code'=>200],200);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getIMEIdisputeList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;

            if($request->bpId) {
                $bpId       = $request->bpId;
            } else {
                $retailId   = $request->retailId;
            }

            $disputeList    = DB::table('imei_disputes')
            ->select('imei_number','description','date')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->get();

            if($disputeList) {
                Log::info('Get IMEI Dispute List By Apps');
                return response()->json($disputeList,200);
            } else {
                Log::warning('IMEI Dispute List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function generalAndtargetIncentiveReport_old(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $bpId        = 0;
            $retailerId  = 0;

            if($request->bpId) {
                $bpId        = $request->bpId;
            } else {
                $retailerId  = $request->retailId;
            }
            
            /*
            $category = $request->groupName;
            
            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
            ->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
            ->where('category','like','%'.$category.'%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->get();

            if($salesIncentiveReportList->isEmpty()) {
                return response()->json(apiResponses(404),404);
            } else {

                $zone_name_list = [];
                foreach($salesIncentiveReportList as $incentiveList)
                {
                    $zoneIdList = json_decode($incentiveList->zone); //exit();
                    foreach($zoneIdList as $zone) {
                        $zone_name_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($incentiveList->zone);
                }
                $incentiveList->zone_name = $zone_name_list;

                return response()->json($salesIncentiveReportList,200);
            }
            */
            
            
            //////////////////////////18-05-2021//////////////////////
            $generalIncentiveList = DB::table('view_sales_incentive_reports')
            ->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
            ->where('category','like','%general%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->get();

            $targetIncentiveList = DB::table('view_sales_incentive_reports')
            ->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
            ->where('category','like','%target%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->get();
            
            if($generalIncentiveList->isEmpty() && $targetIncentiveList->isEmpty()) {
                return response()->json(apiResponses(404),404);
            }
            
            if($generalIncentiveList->isNotEmpty()) {
                
                foreach($generalIncentiveList as $generalIncentive)
                {
                    $general_zone_list = [];
                    $zoneIdList = json_decode($generalIncentive->zone); //exit();
                    foreach($zoneIdList as $zone) {
                        $general_zone_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($generalIncentive->zone);
                    $generalIncentive->zone_name = $general_zone_list;
                }
                
            }
            else {
                $generalIncentiveList = "";
            }
            
            if($targetIncentiveList->isNotEmpty()) {
                
                foreach($targetIncentiveList as $targetIncentive)
                {
                    $target_zone_list = [];
                    $zoneIdList = json_decode($targetIncentive->zone); //exit();
                    foreach($zoneIdList as $zone) {
                        $target_zone_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($targetIncentive->zone);
                    $targetIncentive->zone_name = $target_zone_list;
                }
            }
            else {
                $targetIncentiveList = "";
            }
            return response()->json(["general"=>$generalIncentiveList,"target"=>$targetIncentiveList],200);
            //////////////////////////18-05-2021//////////////////////
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function generalAndtargetIncentiveReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            
            //$startDate = date('d-m-Y',strtotime($request->startDate));
            //$endDate   = date('d-m-Y',strtotime($request->endDate));
            
            $startDate = $request->startDate;
            $endDate   = $request->endDate;
            
            
            $bpId           = 0;
            $retailerId     = 0;

            if($request->bpId > 0) {
                $bpId           = $request->bpId;
            } else {
                $retailerId     = $request->retailId;
            }
            
            $generalIncentiveList = DB::table('view_sales_incentive_reports')
            ->select("retailer_name","bp_name","incentive_title as title","incentive_sale_qty as sale_qty","incentive_amount as amount","product_model as model","zone")
            //->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
            ->where('category','like','%general%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->whereBetween('incentive_date',[$startDate,$endDate])
            ->get();

            $targetIncentiveList = DB::table('view_sales_incentive_reports')
            ->select("retailer_name","bp_name","incentive_title as title","incentive_sale_qty as sale_qty","incentive_amount as amount","product_model as model","zone")
            //->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
            ->where('category','like','%target%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->whereBetween('incentive_date',[$startDate,$endDate])
            ->get();
            
            /*
            if($generalIncentiveList->isEmpty() && $targetIncentiveList->isEmpty()) {
                return response()->json(apiResponses(404),404);
            }
            */
            
            if($generalIncentiveList->isNotEmpty()) {
                
                foreach($generalIncentiveList as $generalIncentive)
                {
                    $general_zone_list = [];
                    $zoneIdList = json_decode($generalIncentive->zone); //exit();
                    foreach($zoneIdList as $zone) {
                        $general_zone_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($generalIncentive->zone);
                    if($bpId > 0){
                        unset($generalIncentive->retailer_name);
                        $bpName = $generalIncentive->bp_name;
                        unset($generalIncentive->bp_name);
                        $generalIncentive->name=$bpName;
                    }
                    else{
                        unset($generalIncentive->bp_name);
                        $RetailerName = $generalIncentive->retailer_name;
                        unset($generalIncentive->retailer_name);
                        $generalIncentive->name=$RetailerName;
                    }
                    //$generalIncentive->zone_name = $general_zone_list;
                }
                
            }
            else {
                $generalIncentiveList = "";
            }
            
            if($targetIncentiveList->isNotEmpty()) {
                
                foreach($targetIncentiveList as $targetIncentive)
                {
                    $target_zone_list = [];
                    $zoneIdList = json_decode($targetIncentive->zone); //exit();
                    foreach($zoneIdList as $zone) {
                        $target_zone_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($targetIncentive->zone);
                    if($bpId > 0){
                        unset($targetIncentive->retailer_name);
                        $bpName = $targetIncentive->bp_name;
                        unset($targetIncentive->bp_name);
                        $targetIncentive->name=$bpName;
                    }
                    else{
                        unset($targetIncentive->bp_name);
                        $RetailerName = $targetIncentive->retailer_name;
                        unset($targetIncentive->retailer_name);
                        $targetIncentive->name=$RetailerName;
                    }
                    //$targetIncentive->zone_name = $target_zone_list;
                }
            }
            else {
                $targetIncentiveList = "";
            }
            
            if($retailerId > 0)
            {
                $retailerPhone  = DB::table('view_retailer_list')
                ->where('id',$retailerId)
                ->value('phone_number');
                
                $startDate = date("Y-M-d",strtotime($startDate));
                $endDate = date("Y-M-d",strtotime($endDate));
                
                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerLiftingIncentive"),$startDate,$endDate,$retailerPhone),"GET");
                $responseData       = json_decode($getCurlResponse['response_data'],true);
                
                $totalAmount    = 0;
                $liftingArray   = [];
                foreach($responseData as $key=>$row) {
                    $totalAmount += $row['RetailerAmount'];
                    
                    $liftingArray[$key]['title'] = $row['Model'];
                    $liftingArray[$key]['sale_qty'] = 1;
                    $liftingArray[$key]['amount'] = $row['RetailerAmount'];
                    $liftingArray[$key]['model'] = $row['Model'];
                    $liftingArray[$key]['name'] = $row['RetailerName'];
                    
                }
                $liftingIncentive = "";
                if(count($responseData) > 0) {
                    $liftingIncentive = $responseData;
                }
                return response()->json(["general"=>$generalIncentiveList,"target"=>$targetIncentiveList,"lifting"=>$liftingArray],200);
            }
            return response()->json(["general"=>$generalIncentiveList,"target"=>$targetIncentiveList,"lifting"=>""],200);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getSalesTarget(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $groupId     = 0;
            $groupName   = "";
            if($request->group == 1) {
                $groupId     = $request->group;
                $groupName   = "BP";
            } else {
                $groupId     = $request->group;
                $groupName   = "Retailer";
            }


            $sales_target = DB::table('incentives')
            ->select("incentive_title","product_model","incentive_type","zone","incentive_amount","min_qty","start_date","end_date")
            ->where('incentive_group',$groupId)
            ->where('incentive_category','like','%target%')
            ->where('status',1)
            ->get();
            

            $model_name_list    = [];
            $zone_name_list     = [];
            $getIncentiveType   = "";

            if($sales_target->isNotEmpty())
            {
                foreach($sales_target as $row)
                {
                    $getModelList       = json_decode($row->product_model);
                    foreach($getModelList as $modelId) {
                        $model_name_list[] = DB::table('product_masters')
                        ->where('product_master_id',$modelId)
                        ->where('status',1)
                        ->value('product_model');
                    }
                    unset($row->product_model);

                    $getIncentiveType   = json_decode($row->incentive_type);
                    unset($row->incentive_type);

                    $zoneIdList         = json_decode($row->zone);
                    foreach($zoneIdList as $zone) {
                        $zone_name_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($row->zone);
                }

                $row->salesModel       = $model_name_list ? $model_name_list:"";
                $row->salesZone        = !empty($zone_name_list) ? $zone_name_list : "";
                $row->incentiveType    = !empty($getIncentiveType) ? $getIncentiveType: "";

                //return response()->json([$groupName."List"=>$sales_target],200);
                return response()->json($sales_target,200);
            }
            return response()->json(apiResponses(404),404);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function checkUserByPhone(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $user_phone = $request->input('phone');

            $userStatus = DB::table('view_check_login_user')
            ->where('employee_phone',$user_phone)
            ->orWhere('brand_promoter_phone',$user_phone)
            ->orWhere('retailer_phone',$user_phone)
            ->first();

            if($userStatus) {

                $otp = mt_rand(100000,999999);

                $addOtp = DB::table('users')
                ->where('id',$userStatus->id)
                ->update([
                    "otp_token"=>$otp
                ]);

                if($addOtp) {
                    $userCode = [
                        "user_id"=>$userStatus->id,
                        "code"=>$otp
                    ];
                    Log::info('User Otp Code Send Success');
                    return response()->json($userCode,200);
                }
            }
            Log::warning('User Not Available');
            return response()->json(apiResponses(401),401);
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function userOtpVerify(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $otp_token      = $request->input('otp_token');
            $user_id        = $request->input('user_id');

            $userStatus = DB::table('view_check_login_user')
            ->where('id',$user_id)
            ->where('otp_token',$otp_token)
            ->first();

            if($userStatus) {
                $verifyCode = [
                    "user_id"=>$userStatus->id,
                    "otp_token"=>$otp_token,
                    "status"=>"true"
                ];
                Log::info('otp Verify Code Send Success');
                return response()->json($verifyCode,200);
            }
            Log::warning('otp Verify Code Send Failed');
            return response()->json(apiResponses(401),401);
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function userPasswordUpdate(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $otp_token      = $request->input('otp_token');
            $user_id        = $request->input('user_id');
            $new_password   = $request->input('password');

            $userStatus = DB::table('view_check_login_user')
            ->where('id',$user_id)
            ->where('otp_token',$otp_token)
            ->first();

            if($userStatus && $otp_token > 0 && !empty($new_password)) {

                $req = Validator::make($request->all(), [
                    'password' => 'required|string|min:5',
                ]);

                if ($req->fails()) {
                    return response()->json($req->errors(), 422);
                }

                $addOtp = DB::table('users')
                ->where('id',$userStatus->id)
                ->update([
                    "otp_token"=>"",
                    "password"=>Hash::make($new_password)
                ]);
                Log::info('User Password Update Success');
                return response()->json(apiResponses(200),200);
            }
            Log::warning('User Password Update Failed'); 
            return response()->json(apiResponses(401),401);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function incentiveStatement(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $bpId           = 0;
            $retailerId     = 0;
            $groupId        = 0;
            $incentiveType  = "";
            
            $startDate      = date('d-m-Y',strtotime($request->startDate));
            $endDate        = date('d-m-Y',strtotime($request->endDate));

            if($request->bpId > 0) {
                $bpId           = $request->bpId;
                $groupId        = 1;
                $incentiveType  = "bp";
            } else {
                $retailerId     = $request->retailId;
                $groupId        = 2;
                $incentiveType  = $request->retailId;
            }
            
            $responseArray = [];
            $generalIncentiveAmount = DB::table('view_sales_incentive_reports')
            ->select("incentive_amount as amount")
            ->where('category','like','%general%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->whereBetween('incentive_date',[$startDate,$endDate])
            ->sum('incentive_amount');
            
            $responseArray[] = [
                "title"=>"General Incentive",
                "amount"=>$generalIncentiveAmount
            ];

            $targetIncentiveAmount = DB::table('view_sales_incentive_reports')
            ->select("incentive_amount as amount")
            ->where('category','like','%target%')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->whereBetween('incentive_date',[$startDate,$endDate])
            ->sum('incentive_amount');
            
            $responseArray[] = [
                "title"=>"Target Incentive",
                "amount"=>$targetIncentiveAmount
            ];
            
            
            $totalAmount    = 0;
            if($retailerId > 0)
            {
                $retailerPhone  = DB::table('view_retailer_list')
                ->where('id',$retailerId)
                ->value('phone_number');
                
                $startDate  = date("Y-M-d",strtotime($startDate));
                $endDate    = date("Y-M-d",strtotime($endDate));
                
                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerLiftingIncentive"),$startDate,$endDate,$retailerPhone),"GET");
                $responseData       = json_decode($getCurlResponse['response_data'],true);
    
                foreach($responseData as $row) {
                    $totalAmount += $row['RetailerAmount'];
                }
            }
            
            $responseArray[] = [
                "title"=>"Lifting Incentive",
                "amount"=>$totalAmount
            ];
            
            $totalSaleAmount = DB::table('sale_products')
            ->select("msrp_price as amount")
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailerId)
            ->sum('msrp_price');
            
            $responseArray[] = [
                "title"=>"Total Sale",
                "amount"=>$totalSaleAmount
            ];
            
            $incentiveLists = DB::table('incentives')
            ->where('incentive_group',$groupId)
            ->where('incentive_category','like','%target%')
            ->where('status',1)
            ->get();
            
            $totalTargetIncentive = 0;
            if($incentiveLists->isNotEmpty()) {
                foreach($incentiveLists as $incentive)
                {
                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                    if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {

                        $totalTargetIncentive = DB::table('view_sales_incentive_reports')
                        ->select("incentive_amount as amount")
                        ->where('category','like','%target%')
                        ->where('bp_id',$bpId)
                        ->where('retailer_id',$retailerId)
                        ->sum('incentive_amount');
                    }
                }
            } 
            
            /*$responseArray[] = [
                "title"=>"Total Target",
                "amount"=>$totalTargetIncentive
            ];*/

            return response()->json($responseArray,200);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function RequestOffLineArray(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $SalesArray = array (
                array(
                    "customer_name"=> "Shimul Mondol",
                    "customer_phone"=> "01552669988",
                    "bp_id"=> "1",
                    "sale_id"=> "1",
                    "retailer_id"=> "0",
                    "address"=>"dhaka,mirpur 2",
                    "photo"=> "1621427130.jpg",
                    "date"=>"2021-05-19",
                    "list"=>array(
                        array(
                            "ime_number"=> "354066116773535",
                            "qty"=> "1",
                            "price"=> "950"
                        ),
                        array(
                            "ime_number"=> "354066116773536",
                            "qty"=> "1",
                            "price"=> "950"
                        ),
                    )
                ),
                array(
                    "customer_name"=> "Mr.Rony",
                    "customer_phone"=> "01552111222",
                    "bp_id"=> "1",
                    "sale_id"=> "1",
                    "retailer_id"=> "0",
                    "address"=>"dhaka,mirpur 2",
                    "photo"=> "1621427130.jpg",
                    "date"=>"2021-05-19",
                    "list"=>array(
                        array(
                            "ime_number"=> "354066116773537",
                            "qty"=> "1",
                            "price"=> "950"
                        )
                    )
                )
            );

            $jsonFeedArray = json_encode(['sales'=>$SalesArray]);

            $getJsonFeed  = json_decode($jsonFeedArray);

            //echo "<pre>";print_r(json_decode($jsonFeedArray));echo "</pre>";die();

            //$getSalesList = json_decode($SalesArray);
            //echo "<pre>";print_r($getSalesList);echo "</pre>";die();

            return response()->json(['sales'=>$SalesArray],200);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    /*
    public function offLineSalesStore(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $jsonFeedArray = $request->input('sales');
            $getJsonFeed   = json_decode($jsonFeedArray);
            
            DB::table('temporaryes')->insert([
                "request_data"=>$jsonFeedArray,
                "date"=>date('d-m-Y'),
            ]);
            return response()->json(apiResponses(200),200);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    */
    
    public function offLineSalesStore(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $jsonFeedArray = $request->input('sales');
            $getJsonFeed   = json_decode($jsonFeedArray);
            $sale_data         = "";
            foreach($getJsonFeed as $row) 
            {
                $DelarDistributionModel = new DelarDistribution;
                $DelarDistributionModel->setConnection('mysql2');
    
                $bpId      = 0;
                $retailId  = 0;
                $groupId   = 0;
    
                if($row->bp_id > 0) {
                    $bpId           = $row->bp_id;
                    $groupId        = 1;
    
                } else {
                    $retailId       = $row->retailer_id;
                    $groupId        = 2;
                }

                $customer_name  = $row->customer_name;
                $customer_phone = $row->customer_phone;
                $saleDate       = $row->date;
                $itemList       = json_decode($row->list);
    
                $imeProductStatus  = [];
                $imeProductResult  = "";
                $saleStatus        = false;
                $saleId            = 0;
                $dealerCode        = 0;
                $productMasterId   = 0;

                foreach ($itemList as $lists) 
                {
                    $getCurlResponse = getData(sprintf(RequestApiUrl("GetIMEIinfo"),$lists->ime_number),"GET");
                    $responseData    = (array) json_decode($getCurlResponse['response_data'],true);

                    if(isset($responseData) && $responseData == "" || empty($responseData)) {
                        Log::error('IMEI Info Not Found ->OutSourceApiController->SalesProduct');
                        return response()->json(apiResponses(404),404);
                    }

                    $productStatus  = ($responseData[0]['IsSoldOut'] == true) ? "0":"1";

                    $getImeResult = $DelarDistributionModel::
                    where('barcode',$lists->ime_number)
                    ->orWhere('barcode2',$lists->ime_number)
                    ->first();
                    
                    //if(isset($getImeResult) && !empty($getImeResult))
                    if(isset($productStatus) && $productStatus > 0)
                    {
                        DB::table('temporaryes')->insert([
                            "request_data"=>1,
                            "date"=>date('d-m-Y'),
                        ]);
                    
                        //$productStatus   = $getImeResult['status'];
                        //$productMasterId = $getImeResult['product_master_id'];
                        //$dealerCode      = $getImeResult['dealer_code'];
    
                        //$productColor = DB::table('colors')
                        //->where('color_id',$getImeResult['color_id'])
                        //->value('name');
                        
                        $dealerCode     = $responseData[0]['DealerCode'];
                        $productId      = $responseData[0]['ProductID'];

                        $imeProductResult = DB::table('view_product_master')
                        ->where('product_id','=',$productId)
                        ->first();

                        $productMasterId    = $imeProductResult->product_master_id;
                        $productColor       = $responseData[0]['Color'];
    
                        $ZoneId    = 0;
                        if($groupId == 1)
                        {
                            $dealerZoneName = DB::table('dealer_informations')
                            ->where('dealer_code',$dealerCode)
                            ->where('alternate_code',$dealerCode)
                            ->value('zone');
    
                            if(!empty($dealerZoneName)) {
                                $ZoneId = DB::table('zones')
                                ->where('zone_name','like','%'.$dealerZoneName.'%')
                                ->value('id');
                            }
                        }
                        else
                        {
                            $getZoneId = DB::table('retailers')
                            ->where('retailer_id',$retailId)
                            ->value('zone_id');
    
                            if($getZoneId != null || !empty($getZoneId)) {
                                $ZoneId = $getZoneId;
                            }
                        }
    
                        $imeProductResult = DB::table('view_product_master')
                        ->where('product_master_id',$productMasterId)
                        ->first();
    
                        $getorderStatus   = []; 
                        if($productStatus == 1 && $productMasterId > 0) {
                            $getorderStatus[]   = 0; //order Sold
                        }
                        else {
                            $getorderStatus[]   = 1; //order Pending
                        }
    
                        $orderStatus = 0;//order Pending 
                        if(in_array(1,$getorderStatus)) {
                            $orderStatus = 1; //order Sold
                        }
    
                        if($saleStatus === true) {
                            $getorderStatus[] = 1;
                        }
                        
                        $image_64           = trim($row->photo); // image base64 encoded
                        $storagePath='';
                        if(!empty($image_64 )) {
                            $ext                = explode(';base64',$row->photo);
                            $ext                = explode('/',$ext[0]);            
                            $extension          = ($ext[1]) ? $ext[1]:".jpg";
        
                            $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                            $image              = str_replace($replace, '', $image_64); 
                            $image              = str_replace(' ', '+', $image);
                            $imageName          = time().'.'.$extension;
        
                            Storage::disk('public')->put($imageName, base64_decode($image));
        
                            $baseUrl        = URL::to('');
                            $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                        }
                        
                        if($saleStatus === false) {
                            Sale::create([
                                "customer_name"=>$customer_name,
                                "customer_phone"=>$customer_phone,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "dealer_code"=> $dealerCode,
                                "sale_date"=>$saleDate,
                                "photo"=> $storagePath,
                                "status"=>0,
                        		"order_type"=>2,//1=Online,2=Offline
                            ]);
                            
                            $saleId = DB::getPdo()->lastInsertId();
                            $saleStatus = true;
                            $getorderStatus[] = 0;
                        }
    
                        if(in_array(1,$getorderStatus)) {
                            Sale::where('id',$saleId)->update(["status"=>$orderStatus]);
                        }
    
                        if(!empty($saleId)) {
                            SaleProduct::create([
                                "sales_id"=>$saleId,
                                "ime_number"=> $lists->ime_number,
                                "dealer_code"=> $dealerCode,
                                "product_master_id"=> $productMasterId,
                                "product_id"=> $imeProductResult->product_id,
                                "product_code"=>  $imeProductResult->product_code,
                                "product_type"=> $imeProductResult->product_type,
                                "product_model"=> $imeProductResult->product_model,
                                "product_color"=> $productColor ? $productColor:'Others',
                                "category"=> $imeProductResult->category2,
                                "mrp_price"=> $imeProductResult->mrp_price,
                                "msdp_price"=> $imeProductResult->msdp_price,
                                "msrp_price"=> $imeProductResult->msrp_price,
                                "sale_price"=> $lists->price,
                                "sale_qty"=> $lists->qty,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "product_status"=>0 //0=sold order 1=pending order
                            ]);
                            //Ime Database Product Status Update Start
                            $getCurlResponse = getData(sprintf(RequestApiUrl("UpdateIMEIStatus"),$lists->ime_number),"GET");
                        }
    
                        $sale_data = [
                            "sale_id"=>$saleId,
                            "bp_id"=> $bpId,
                            "retailer_id"=> $retailId,
                            "sale_date"=>$saleDate,
                            "customer_name"=> $customer_name,
                            "customer_phone"=>  $customer_phone
                        ];
    
                        $saleQty        = $lists->qty;
                        $sale_date      = $saleDate;
    
                        $incentiveType  = $groupId == 1 ? "bp":$retailId;
    
                        $incentiveLists = DB::table('incentives')
                        ->where('incentive_group',$groupId)
                        ->where('start_date','<=',$sale_date)
                        ->where('end_date','>=',$sale_date)
                        ->where('status',1)
                        ->get();
        
                        if($incentiveLists->isNotEmpty()) {
                                foreach($incentiveLists as $incentive)
                                {
                                    $getModelId         = json_decode($incentive->product_model,TRUE);
                                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                                    $getZone            = json_decode($incentive->zone,TRUE);
                                    $minQty             = $incentive->min_qty;
        
                                    $totalSaleQty = DB::table('view_sales_reports')
                                    ->where('product_master_id',$productMasterId)
                                    //->whereBetween('sale_date',[$start_date,$end_date])
                                    ->sum('view_sales_reports.sale_qty');
                                    
                                    if(in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                                        if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                                            if(in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                                                if($totalSaleQty >= $minQty) {
        
                                                    DB::table('sale_incentives')
                                                    ->insert([
                                                        "incentive_category"=>$incentive->incentive_category,
                                                        "ime_number"=>$lists->ime_number,
                                                        "sale_id" =>$saleId, 
                                                        "bp_id" =>$bpId,
                                                        "retailer_id"=>$retailId,
                                                        "incentive_title"=>$incentive->incentive_title,
                                                        "product_model"=>$imeProductResult->product_model,
                                                        "zone"=>$incentive->zone,
                                                        "incentive_amount"=>$incentive->incentive_amount,
                                                        "incentive_min_qty"=>$incentive->min_qty,
                                                        "incentive_sale_qty"=>$saleQty,
                                                        "total_amount"=>$saleQty*$incentive->incentive_amount,
                                                        "start_date"=>$incentive->start_date,
                                                        "end_date"=>$incentive->end_date,
                                                        "incentive_date"=>date('Y-m-d'),
                                                        "incentive_status"=>$incentive->status
                                                    ]);
        
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                    }
                    else
                    {
                        $imeiCheck = SaleProduct::where('ime_number','=',$lists->ime_number)->first();
                        
                        if(empty($imeiCheck) || $imeiCheck == null)
                        {
                            $image_64        = trim($row->photo); // image base64 encoded
                            $storagePath='';
                            if(!empty($image_64 )) {
                                $ext                = explode(';base64',$row->photo);
                                $ext                = explode('/',$ext[0]);            
                                $extension          = ($ext[1]) ? $ext[1]:".jpg";
            
                                $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                                $image              = str_replace($replace, '', $image_64); 
                                $image              = str_replace(' ', '+', $image);
                                $imageName          = time().'.'.$extension;
            
                                Storage::disk('public')->put($imageName, base64_decode($image));
            
                                $baseUrl        = URL::to('');
                                $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                            }
                            
                            if($saleStatus === false) {
                            	Sale::create([
                            		"customer_name"=>$customer_name,
                            		"customer_phone"=>$customer_phone,
                            		"bp_id"=> $bpId ? $bpId:0,
                            		"retailer_id"=> $retailId ? $retailId:0,
                            		"dealer_code"=> $dealerCode ? $dealerCode:0,
                            		"sale_date"=>$saleDate,
                            		"photo"=> $storagePath ? $storagePath:"no-image.png",
                            		"status"=>1,
                            		"order_type"=>2,//1=Online,2=Offline
                            	]);
                            	$saleId = DB::getPdo()->lastInsertId();
                            	$saleStatus = true;
                            	$getorderStatus[] = 0;
                            }
        
                            if(in_array(1,$getorderStatus)) {
                            	Sale::where('id',$saleId)
                            	->update(["status"=>$orderStatus]);
                            }
                            
                            if($saleStatus === true) {
                                Sale::where('id',$saleId)
                                ->update([
                                    "status"=>1
                                ]);
                            }
        
                            if(!empty($saleId)) {
                            	SaleProduct::create([
                            		"sales_id"=>$saleId,
                            		"ime_number"=> $lists->ime_number,
                            		"dealer_code"=> $dealerCode ? $dealerCode:0,
                            		"product_master_id"=> $productMasterId ? $productMasterId:0,
                            		"product_id"=> 0,
                            		"product_code"=>  0,
                            		"product_type"=> 0,
                            		"product_model"=> 0,
                            		"product_color"=> 'Others',
                            		"category"=> 0,
                            		"mrp_price"=> '0.00',
                            		"msdp_price"=> '0.00',
                            		"msrp_price"=> '0.00',
                            		"sale_price"=> $lists->price,
                            		"sale_qty"=> $lists->qty,
                            		"bp_id"=> $bpId ? $bpId:0,
                            		"retailer_id"=> $retailId ? $retailId:0,
                            		"product_status"=>1 //0=sold 1=pending
                            	]);
                            	//Ime Database Product Status Update Start
                                $getCurlResponse = getData(sprintf(RequestApiUrl("UpdateIMEIStatus"),$lists->ime_number),"GET");
                                }
                            
                            $sale_data = [
                            	"sale_id"=>$saleId,
                            	"bp_id"=> $bpId ? $bpId:0,
                            	"retailer_id"=> $retailId ? $retailId:0,
                            	"sale_date"=>$saleDate,
                            	"customer_name"=> $customer_name,
                            	"customer_phone"=>  $customer_phone
                            ];
                        }
                        else
                        {
                            return response()->json(apiResponses(404),404);
                        }
                    }
                }
            }
            
            if(isset($sale_data) && !empty($sale_data)) 
            {
                Log::info('Offline Product Sales Success');
                return response()->json(apiResponses(200),200);
            }
            else 
            {
                Log::warning('Offline Product Sales Failed ->OutSourceApiController->offLineSalesStore');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function offLineSuccessStore(){
        foreach($getJsonFeed as $row) {
                
                $DelarDistributionModel = new DelarDistribution;
                $DelarDistributionModel->setConnection('mysql2');

                $bpId      = 0;
                $retailId  = 0;
                $groupId   = 0;

                if($row->bp_id > 0) {
                    $bpId           = $row->bp_id;
                    $groupId        = 1;

                } else {
                    $retailId       = $row->retailer_id;
                    $groupId        = 2;
                }
                $customer_name  = $row->customer_name;
                $customer_phone = $row->customer_phone;
                $saleDate       = $row->date;
                $itemList       = json_decode($row->list);

                $imeProductStatus  = [];
                $imeProductResult  = "";
                $saleStatus        = false;
                $sale_data         = "";
                $saleId            = "";

                foreach ($itemList as $lists) {

                    $getImeResult = $DelarDistributionModel::
                    where('barcode',$lists->ime_number)
                    ->orWhere('barcode2',$lists->ime_number)
                    ->first();
                    
                    if(isset($getImeResult)) 
                    {
                        $productStatus   = $getImeResult['status'];
                        $productMasterId = $getImeResult['product_master_id'];
                        $dealerCode      = $getImeResult['dealer_code'];

                        $productColor = DB::table('colors')
                        ->where('color_id',$getImeResult['color_id'])
                        ->value('name');

                        $ZoneId    = 0;
                        if($groupId == 1)
                        {
                            $dealerZoneName = DB::table('dealer_informations')
                            ->where('dealer_code',$dealerCode)
                            ->where('alternate_code',$dealerCode)
                            ->value('zone');

                            if(!empty($dealerZoneName)) {
                                $ZoneId = DB::table('zones')
                                ->where('zone_name','like','%'.$dealerZoneName.'%')
                                ->value('id');
                            }
                        }
                        else
                        {
                            $getZoneId = DB::table('retailers')
                            ->where('retailer_id',$retailId)
                            ->value('zone_id');

                            if($getZoneId != null || !empty($getZoneId)) {
                                $ZoneId = $getZoneId;
                            }
                        }

                        $imeProductResult = DB::table('view_product_master')
                        ->where('product_master_id',$productMasterId)
                        ->first();

                        $getorderStatus   = []; 
                        if($productStatus == 1 && $productMasterId > 0) {
                            $getorderStatus[]   = 0; //order Sold
                        }
                        else {
                            $getorderStatus[]   = 1; //order Pending
                        }

                        $orderStatus = 0;//order Pending 
                        if(in_array(1,$getorderStatus)) {
                            $orderStatus = 1; //order Sold
                        }

                        if($saleStatus === true) {
                            $getorderStatus[] = 1;
                        }
                        
                        $image_64           = trim($row->photo); // image base64 encoded
                        $storagePath='';
                        if(!empty($image_64 )) {
                        $ext                = explode(';base64',$row->photo);
                        $ext                = explode('/',$ext[0]);            
                        $extension          = ($ext[1]) ? $ext[1]:".jpg";

                        $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                        $image              = str_replace($replace, '', $image_64); 
                        $image              = str_replace(' ', '+', $image);
                        $imageName          = time().'.'.$extension;

                        Storage::disk('public')->put($imageName, base64_decode($image));

                        $baseUrl        = URL::to('');
                        $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                        }
                    

                        if($saleStatus === false) {
                            Sale::create([
                                "customer_name"=>$customer_name,
                                "customer_phone"=>$customer_phone,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "dealer_code"=> $dealerCode,
                                "sale_date"=>$saleDate,
                                "photo"=> $storagePath,
                                "status"=>0
                            ]);
                            $saleId = DB::getPdo()->lastInsertId();
                            $saleStatus = true;
                            $getorderStatus[] = 0;
                        }

                        if(in_array(1,$getorderStatus)) {
                            Sale::where('id',$saleId)
                            ->update(["status"=>$orderStatus]);
                        }

                        if(!empty($saleId)) {
                            SaleProduct::create([
                                "sales_id"=>$saleId,
                                "ime_number"=> $lists->ime_number,
                                "dealer_code"=> $dealerCode,
                                "product_master_id"=> $productMasterId,
                                "product_id"=> $imeProductResult->product_id,
                                "product_code"=>  $imeProductResult->product_code,
                                "product_type"=> $imeProductResult->product_type,
                                "product_model"=> $imeProductResult->product_model,
                                "product_color"=> $productColor ? $productColor:'Others',
                                "category"=> $imeProductResult->category2,
                                "mrp_price"=> $imeProductResult->mrp_price,
                                "msdp_price"=> $imeProductResult->msdp_price,
                                "msrp_price"=> $imeProductResult->msrp_price,
                                "sale_price"=> $lists->price,
                                "sale_qty"=> $lists->qty,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "product_status"=>0 //Sold Order
                            ]);
                            //Ime Database Product Status Update Start
                            $DelarDistributionModel::
                            where('barcode',$lists->ime_number)
                            ->orWhere('barcode2',$lists->ime_number)
                            ->update([
                                "status"=>0,
                            ]);
                        }

                        $sale_data = [
                            "sale_id"=>$saleId,
                            "bp_id"=> $bpId,
                            "retailer_id"=> $retailId,
                            "sale_date"=>$saleDate,
                            "customer_name"=> $customer_name,
                            "customer_phone"=>  $customer_phone
                        ];

                        $saleQty        = $lists->qty;
                        $saleId         = $saleId;
                        $sale_date      = $saleDate;

                        $incentiveType  = $groupId == 1 ? "bp":$retailId;

                        $incentiveLists = DB::table('incentives')
                        ->where('incentive_group',$groupId)
                        ->where('start_date','<=',$sale_date)
                        ->where('end_date','>=',$sale_date)
                        ->where('status',1)
                        ->get();

                        if($incentiveLists->isNotEmpty()) {
                            foreach($incentiveLists as $incentive)
                            {
                                $getModelId         = json_decode($incentive->product_model,TRUE);
                                $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                                $getZone            = json_decode($incentive->zone,TRUE);
                                $minQty             = $incentive->min_qty;

                                $totalSaleQty = DB::table('view_sales_reports')
                                ->where('product_master_id',$productMasterId)
                                //->whereBetween('sale_date',[$start_date,$end_date])
                                ->sum('view_sales_reports.sale_qty');
                                

                                if(in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                                    if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                                        if(in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                                            if($totalSaleQty >= $minQty) {

                                                DB::table('sale_incentives')
                                                ->insert([
                                                    "incentive_category"=>$incentive->incentive_category,
                                                    "ime_number"=>$lists->ime_number,
                                                    "sale_id" =>$saleId, 
                                                    "bp_id" =>$bpId,
                                                    "retailer_id"=>$retailId,
                                                    "incentive_title"=>$incentive->incentive_title,
                                                    "product_model"=>$imeProductResult->product_model,
                                                    "zone"=>$incentive->zone,
                                                    "incentive_amount"=>$incentive->incentive_amount,
                                                    "incentive_min_qty"=>$incentive->min_qty,
                                                    "incentive_sale_qty"=>$saleQty,
                                                    "total_amount"=>$saleQty*$incentive->incentive_amount,
                                                    "start_date"=>$incentive->start_date,
                                                    "end_date"=>$incentive->end_date,
                                                    "incentive_date"=>date('Y-m-d'),
                                                    "incentive_status"=>$incentive->status
                                                ]);

                                            }
                                        }
                                    }
                                }
                                
                            }
                        }
                    }
                    else
                    {
                        //return response()->json(apiResponses(422,'Invalid IMEI Number'),422);
                        /*$image_64           = trim($row->photo); // image base64 encoded
                        $storagePath='';
                        if(!empty($image_64 )){
                        $ext                = explode(';base64',$row->photo);
                        $ext                = explode('/',$ext[0]);            
                        $extension          = ($ext[1]) ? $ext[1]:".jpg";
                        
                        $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                        $image              = str_replace($replace, '', $image_64); 
                        $image              = str_replace(' ', '+', $image);
                        $imageName          = time().'.'.$extension;
                        
                        Storage::disk('public')->put($imageName, base64_decode($image));
                        
                        $baseUrl        = URL::to('');
                        $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                        */

                        if($saleStatus === false) {
                        	Sale::create([
                        		"customer_name"=>$customer_name,
                        		"customer_phone"=>$customer_phone,
                        		"bp_id"=> $bpId,
                        		"retailer_id"=> $retailId,
                        		"dealer_code"=> "",
                        		"sale_date"=>$saleDate,
                        		"photo"=> "",
                        		"status"=>0
                        	]);
                        	$saleId = DB::getPdo()->lastInsertId();
                        	$saleStatus = true;
                        	$getorderStatus[] = 0;
                        }

                        if(in_array(1,$getorderStatus)) {
                        	Sale::where('id',$saleId)
                        	->update(["status"=>$orderStatus]);
                        }

                        if(!empty($saleId)) {
                        	SaleProduct::create([
                        		"sales_id"=>$saleId,
                        		"ime_number"=> $lists->ime_number,
                        		"dealer_code"=> $dealerCode,
                        		"product_master_id"=> $productMasterId ? $productMasterId:0,
                        		"product_id"=> 0,
                        		"product_code"=>  0,
                        		"product_type"=> 0,
                        		"product_model"=> 0,
                        		"product_color"=> 'Others',
                        		"category"=> 0,
                        		"mrp_price"=> '0.00',
                        		"msdp_price"=> '0.00',
                        		"msrp_price"=> '0.00',
                        		"sale_price"=> $lists->price,
                        		"sale_qty"=> $lists->qty,
                        		"bp_id"=> $bpId,
                        		"retailer_id"=> $retailId,
                        		"product_status"=>0 //Sold Order
                        	]);
                        	//Ime Database Product Status Update Start
                        	$DelarDistributionModel::
                        	where('barcode',$lists->ime_number)
                        	->orWhere('barcode2',$lists->ime_number)
                        	->update([
                        		"status"=>0,
                        	]);
                        }

                        $sale_data = [
                        	"sale_id"=>$saleId,
                        	"bp_id"=> $bpId,
                        	"retailer_id"=> $retailId,
                        	"sale_date"=>$saleDate,
                        	"customer_name"=> $customer_name,
                        	"customer_phone"=>  $customer_phone
                        ];
                        /*
                        $saleQty        = $lists->qty;
                        $saleId         = $saleId;
                        $sale_date      = $saleDate;

                        $incentiveType  = $groupId == 1 ? "bp":$retailId;
                        
                        $incentiveLists = DB::table('incentives')
                        ->where('incentive_group',$groupId)
                        ->where('start_date','<=',$sale_date)
                        ->where('end_date','>=',$sale_date)
                        ->where('status',1)
                        ->get();

                        if($incentiveLists->isNotEmpty()) {
                        	foreach($incentiveLists as $incentive)
                        	{
                        		$getModelId         = json_decode($incentive->product_model,TRUE);
                        		$getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                        		$getZone            = json_decode($incentive->zone,TRUE);
                        		$minQty             = $incentive->min_qty;
                        
                        		$totalSaleQty = DB::table('view_sales_reports')
                        		->where('product_master_id',$productMasterId)
                        		//->whereBetween('sale_date',[$start_date,$end_date])
                        		->sum('view_sales_reports.sale_qty');
                        		
                        
                        		if(in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                        			if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                        				if(in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                        					if($totalSaleQty >= $minQty) {
                        
                        						DB::table('sale_incentives')
                        						->insert([
                        							"incentive_category"=>$incentive->incentive_category,
                        							"ime_number"=>$lists->ime_number,
                        							"sale_id" =>$saleId, 
                        							"bp_id" =>$bpId,
                        							"retailer_id"=>$retailId,
                        							"incentive_title"=>$incentive->incentive_title,
                        							"product_model"=>$imeProductResult->product_model,
                        							"zone"=>$incentive->zone,
                        							"incentive_amount"=>$incentive->incentive_amount,
                        							"incentive_min_qty"=>$incentive->min_qty,
                        							"incentive_sale_qty"=>$saleQty,
                        							"total_amount"=>$saleQty*$incentive->incentive_amount,
                        							"start_date"=>$incentive->start_date,
                        							"end_date"=>$incentive->end_date,
                        							"incentive_date"=>date('d-m-Y'),
                        							"incentive_status"=>$incentive->status
                        						]);
                        
                        					}
                        				}
                        			}
                        		}
                        		
                        	}
                        }
                        */
                        
                    }
                }
                    
            }

            if(isset($sale_data) && !empty($sale_data)) {
                return response()->json(apiResponses(200),200);
            }
            else {
                return response()->json(apiResponses(404),404);
            }
    }
    
    public function getRetailerLiftingIncentive(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $retailerId = 0;
            if($request->retailerId) {
                $retailerId = $request->retailerId;
            } else {
                $retailerId = auth('api')->user()->id;
            }

            $startDate = $request->startDate;
            $endDate   = $request->endDate;

            $retailerPhone = DB::table('view_retailer_list')
            ->where('id',$retailerId)
            ->value('phone_number');

            $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerLiftingIncentive"),$startDate,$endDate,$retailerPhone),"GET");
            $responseData       = json_decode($getCurlResponse['response_data'],true);
            
            $totalAmount = 0;
            foreach($responseData as $row) {
                $totalAmount += $row['RetailerAmount'];
            }
            
            if(isset($responseData)) {
                if(count($responseData) > 0) {
                    Log::info('Get Retailer Lifting Incentive');
                    return response()->json(["RetailerLiftingIncentive"=>$responseData,"liftingIncentiveAmount"=>$totalAmount],200);
                } else {
                    Log::info('Retailer Lifting Incentive Not Found');
                    return response()->json(apiResponses(404),404);
                }
            } else {
                return response()->json(apiResponses(404),404);exit();
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPreBookingList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true) {

            $requestDate    = date('Y-m-d');

            $preBookingList = PreBookin::select('id','model as Model','color as Color','start_date as startDate','end_date as EndDate','minimum_advance_amount as MinimumAdvanceAmount','max_qty as MaxQty','price as Price')
            ->where('start_date', '<=', $requestDate)
            ->where('end_date', '>=', $requestDate)
            ->where('status','=',1)
            ->get();

            if(isset($preBookingList) && $preBookingList->isNotEmpty()) {
                Log::info('Get Pre-Booking List By Apps');
                return response()->json($preBookingList,200);
            } else {
                Log::warning('Pre-Booking List Not Found');
                return response()->json(apiResponses(404),404);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function OrderPreBooking(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bpId      = 0;
            $retailId  = 0;
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
            }
    
            $preBookingId       = $request->input('pre_booking_id');
            $customerName       = $request->input('customer_name');
            $customerPhone      = $request->input('customer_phone');
            $customerAddress    = $request->input('customer_address');
            $model              = $request->input('model');
            $color              = $request->input('color');
            $qty                = $request->input('qty');
            $advanced_payment   = $request->input('advanced_payment');
            $bookingDate        = date('Y-m-d');

            $checkCustomerBookingQty = DB::table('prebooking_orders')
            ->where('prebooking_id',$preBookingId)
            ->where('customer_phone','=',$customerPhone)
            ->where('model','=',$model)
            ->sum('qty');

            $totalBookingQty = $checkCustomerBookingQty + $qty;

            $checkStatus = PreBookin::where('model','=',$model)
            ->where('model','=',$model)
            ->where('max_qty','>=',$totalBookingQty)
            ->where('minimum_advance_amount','<=',$advanced_payment)
            ->where('start_date', '<=', $bookingDate)
            ->where('end_date', '>=', $bookingDate)
            ->first();

            if($checkStatus) {

                $orderStatus = DB::table('prebooking_orders')
                ->insert([
                    "prebooking_id" =>$checkStatus->id,
                    "bp_id"=>$bpId,
                    "retailer_id"=>$retailId,
                    "customer_name" =>$customerName,
                    "customer_phone" =>$customerPhone,
                    "customer_address" =>$customerAddress,
                    "model" => $model,
                    "color" => $color,
                    "qty" => $qty,
                    "advanced_payment" => $advanced_payment,
                    "booking_date" => date('Y-m-d H:i:s'),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                if($orderStatus) {
                    Log::info('Order Pre-Booking Success');
                    return response()->json(apiResponses(200),200);
                } else {
                    Log::info('Order Pre-Booking Failed');
                    return response()->json(apiResponses(401),401);
                }
            } 
            else {
                Log::error('Invalid Pre-Booking Order');
                return response()->json(apiResponses(406),406);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getPreBookingOrderList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $startDate = $request->startDate;
            $endDate   = $request->endDate;

            $bpId      = 0;
            $retailId  = 0;
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
            }

            $preBookingOrderList =  DB::table('prebooking_orders')
            ->select('customer_name as CustomerName','customer_phone as CustomerPhone','customer_address as CustomerAddress','model as Model','color as Color','qty as Qty','advanced_payment as AdvancedPayment','booking_date as BookingDate')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->whereBetween('booking_date',[$startDate,$endDate])
            ->get();

            if(isset($preBookingOrderList) && $preBookingOrderList->isNotEmpty()) {
                Log::info('Get Pre-Booking List By Apps');
                return response()->json($preBookingOrderList,200);
            } else {
                Log::warning('Pre-Booking List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function deviceRegistraction(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        //dd($request->all());

        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bp_id              = $request->input('bp_id');
            $retailer_id        = $request->input('retailer_id');
            $registration_id    = $request->input('registration_id');
            $uuid               = $request->input('uuid');
            $platform           = $request->input('platform');
            $version            = $request->input('version');
            $intsallation_date  = $request->input('intsallation_date');

            $deviceRegistrationCheck = DB::table('device_registrations')
            ->where('registration_id','=',$registration_id)
            ->first();
            $status = 0;
            if($deviceRegistrationCheck) {
                $updateRegistraction = DB::table('device_registrations')
                ->where('registration_id','=',$registration_id)
                ->update([
                    "bp_id"=> $request->input('bp_id'),
                    "retailer_id"=> $request->input('retailer_id'),
                     "uuid"=>$uuid,
                    "platform"=> $request->input('platform'),
                    "version"=> $request->input('version'),
                    "intsallation_date"=> $request->input('intsallation_date'),
                    "updated_at"=>Carbon::now()
                ]);
                 $status = 1;
                if($updateRegistraction) {
              
                    Log::info('Device Registraction Success');
                }
            } else {

                $addRegistraction = DB::table('device_registrations')
                ->insert([
                    "bp_id"=> $request->input('bp_id'),
                    "retailer_id"=> $request->input('retailer_id'),
                    "registration_id"=> $request->input('registration_id'),
                    "uuid"=>$uuid,
                    "platform"=> $request->input('platform'),
                    "version"=> $request->input('version'),
                    "intsallation_date"=> $request->input('intsallation_date'),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
                if($addRegistraction) {
                    $status = 1;
                    Log::info('Device Registraction Success');
                }
            }

            if($status == 1) {
                return response()->json(apiResponses(200),200);
            } else {
                Log::warning('Device Registraction Failed ->OutSourceApiController->deviceRegistraction');
                return response()->json(apiResponses(200),200);
            }

        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function SendPushNotification($id)
    {
        $PushNotificationInfo = PushNotification::where('id',$id)->first();
        return response()->json('success');
    }
    
    public function getMonthlySalesPercentage(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId           = 0;
            $retailerId     = 0;
            $incentiveGroup = 0;
            $zoneId         = 0;
            $month_Sdate    =  date('Y-m-01');
            $month_Edate    =  date('Y-m-t');


            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) 
            {
                $bpId = $userExists->bp_id;

                $incentiveGroup     = 1;

                $zoneName = DB::table('brand_promoters')
                ->where('id',$bpId)
                ->where('status',1)
                ->value('distributor_zone');

                $zoneId = DB::table('zones')
                ->where('zone_name',$zoneName)
                ->value('id');
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) 
            {
                $retailerId = $userExists->retailer_id;
                $incentiveGroup  = 2;

                $zoneId = DB::table('retailers')
                ->where('id',$retailerId)
                ->where('status',1)
                ->value('zone_id');
            }

            $incentiveAvailabelOrNot = DB::table('view_incentive_list')
            ->where('incentive_group','=',$incentiveGroup)
            ->where('start_date','<=',$month_Sdate)
            ->where('end_date','>=',$month_Edate)
            ->where('status',1)
            ->get();

            $targetSaleQty  = 0;
            $totalSaleQty   = 0;


            if(isset($incentiveAvailabelOrNot) && $incentiveAvailabelOrNot->isNotEmpty()) 
            {
                foreach($incentiveAvailabelOrNot as $row) 
                {
                    $targetSaleQty     = $row->min_qty;
                    $incentiveZoneList = json_decode($row->zone);
                    foreach($incentiveZoneList as $key=>$val) 
                    {
                        if($val == 'all' || $val == $zoneId) 
                        {
                            $totalSaleQty = DB::table('view_sales_incentive_reports')
                            ->where('bp_id',$bpId)
                            ->where('retailer_id',$retailerId)
                            ->where('start_date','<=',$month_Sdate)
                            ->where('end_date','>=',$month_Edate)
                            ->sum('incentive_sale_qty');
                        }
                    }
                }
            }
            //echo  round((50 * 100) / 50, 2); exit();
            //echo "Sq-".$totalSaleQty."~"."Mq-".$targetSaleQty;exit();
            
            if($targetSaleQty > 0) 
            {
                $getAchievment = round(($totalSaleQty * 100) / $targetSaleQty, 2);
    
                if($getAchievment > 0 || $getAchievment > 100) 
                {
                    return response()->json($getAchievment,200);
                } 
                else 
                {
                    $getAchievment = 0;
                    return response()->json($getAchievment,200);
                }
            }
            else 
            {
                $getAchievment = 0;
                return response()->json($getAchievment,200);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPushNotificationList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true)
        {
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $successNotificationList = PushNotification::select('title','message','date')
            ->where('send_status',1)
            ->get();

            if(isset($successNotificationList) && $successNotificationList->isNotEmpty()) {
                Log::info('Get Push Notification List By Apps');
                return response()->json($successNotificationList,200);
            } else {
                Log::warning('Pre-Booking List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
}
