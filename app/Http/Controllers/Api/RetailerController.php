<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DealerInformation;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Validator;
use DataTables;
use Response;

class RetailerController extends Controller
{
    
    public function index(Request $request)
    {
        $CategoryList = DB::table('bp_retailer_categories')
        ->where('status','=',1)
        ->get();
        $RetailerList = GetTableWithPagination('view_retailer_list',100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $RetailerList = DB::table('view_retailer_list')
                ->where('id',$query)
                ->orWhere('retailer_name','like', '%'.$query.'%')
                ->orWhere('owner_name', 'like', '%'.$query.'%')
                ->orWhere('phone_number', 'like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.retailer.result_data', compact('RetailerList','CategoryList'))->render();
        }

        if(isset($RetailerList) && $RetailerList->isNotEmpty()) {
            Log::info('Load Retailer List');
        } else {
            Log::warning('Retailer List Not Found');
        }
        return view('admin.retailer.list',compact('RetailerList','CategoryList'));
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {        
        $rules = [
            'retailer_name'=>'required',
			'owner_name'=>'required',
			'phone_number'=>'required',
            'retailder_address'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Retailer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $retailer_phone   	= $request->input('phone_number');
        $CheckRetailer      = retailer::where('phone_number',$retailer_phone)->first();
        

        $paymentType   = $request->input('payment_type') ? $request->input('payment_type') : 1;
        $paymentNumber = $request->input('payment_number') ? $request->input('payment_number'):$request->input('api_payment_number');

        $paymentAgent = "";
        if($request->input('agent_name') != null) {
            $paymentAgent  = $request->input('agent_name');
        } else {
            $paymentAgent  = $request->input('api_payment_type');
        }

        $paymentBankName = "";
        if($request->input('bank_name') != null) {
            $paymentBankName  = $request->input('bank_name');
        } else {
            $paymentBankName  = "";
        }

        if($CheckRetailer) {
            $CheckUserTable = User::where('retailer_id',$CheckRetailer['id'])->first();
            $UpdateRetailer = retailer::where('id',$CheckRetailer['id'])
            ->update([
                "category_id"=>$request->input('category_id'),
            	"retailer_id"=>$request->input('retailer_id'),
            	"retailer_name"=>$request->input('retailer_name'),
            	"retailder_address"=>$request->input('retailder_address'),
				"owner_name"=>$request->input('owner_name'),
				"phone_number"=>$request->input('phone_number'),
                "bank_name"=>$paymentBankName,
                "agent_name"=>$paymentAgent,
				"payment_type"=>$paymentType,
				"payment_number"=>$paymentNumber,
				"zone_id"=>$request->input('zone_id'),
				"division_id"=>$request->input('division_id'),
				"division_name"=>$request->input('division_name'),
				"distric_id"=>$request->input('distric_id'),
				"distric_name"=>$request->input('distric_name'),
				"police_station"=>$request->input('police_station'),
				"thana_id"=>$request->input('thana_id'),
				"distributor_code"=>$request->input('distributor_code'),
				"distributor_code2"=>$request->input('distributor_code2'),
				"status"=>$request->input('status')
            ]);

            if($UpdateRetailer) {
                if(isset($CheckUserTable) && !empty($CheckUserTable)){
                    $UpdateUser = User::where('retailer_id',$CheckRetailer['id'])
                    ->update([
                        "name"=>$request->input('retailer_name'),
                        "email"=>$request->input('retailer_name').'@waltonbd.com'
                    ]);
                }
            }
            Log::info('Existing Retailer Updated');
            return response()->json('success');
        } else {
            $AddRetailer = retailer::create([
                "category_id"=>$request->input('category_id'),
                "retailer_id"=>$request->input('retailer_id'),
            	"retailer_name"=>$request->input('retailer_name'),
            	"retailder_address"=>$request->input('retailder_address'),
				"owner_name"=>$request->input('owner_name'),
				"phone_number"=>$request->input('phone_number'),
                "bank_name"=>$paymentBankName,
                "agent_name"=>$paymentAgent,
				"payment_type"=>$paymentType,
				"payment_number"=>$paymentNumber,
				"zone_id"=>$request->input('zone_id'),
				"division_id"=>$request->input('division_id'),
				"division_name"=>$request->input('division_name'),
				"distric_id"=>$request->input('distric_id'),
				"distric_name"=>$request->input('distric_name'),
				"police_station"=>$request->input('police_station'),
				"thana_id"=>$request->input('thana_id'),
				"distributor_code"=>$request->input('distributor_code'),
				"distributor_code2"=>$request->input('distributor_code2'),
				"status"=>$request->input('status')
            ]);

            $id = DB::getPdo()->lastInsertId();
            if($AddRetailer) {
                $AddUser = User::create([
                    "name"=>$request->input('retailer_name'),
                    "retailer_id"=>$id,
                    "email"=>$request->input('retailer_name').'@waltonbd.com',
                    "password"=>Hash::make('phone_number@rg'),
                    "password_confirmation"=>Hash::make('phone_number@rg')
                ]);
            }

            Log::info('Create Retailer');
            return response()->json('success');
        }
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $RetailerInfo = DB::table('view_retailer_list')
            ->where('id',$id)
            ->first();

            if($RetailerInfo) {
                Log::info('Get Retailer By Id');
                return response()->json($RetailerInfo);
            } else {
                Log::warning('Retailer Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Retailer Not Found By Id');
            return response()->json('error'); 
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'retailer_name'=>'required',
            'owner_name'=>'required',
            'phone_number'=>'required', 
            'retailder_address'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Update Retailer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $RetailerUpdateID   = $request->input('update_id');
        $retailer_id        = $request->input('retailer_id');
        $retailer_phone     = $request->input('phone_number');
        $category_id        = $request->input('category_id');
        $CheckRetailer      = retailer::where('phone_number',$retailer_phone)
        ->where('id','<>',$RetailerUpdateID)
        ->first();

        $CheckUserTable         = User::where('retailer_id',$RetailerUpdateID)->first();

        if(!$CheckRetailer) {
            $UpdateRetailer = retailer::where('id',$RetailerUpdateID)
            ->update([
                "category_id"=>$category_id,
                "retailer_id"=>$request->input('retailer_id'),
                "retailer_name"=>$request->input('retailer_name'),
                "retailder_address"=>$request->input('retailder_address'),
                "owner_name"=>$request->input('owner_name'),
                "phone_number"=>$request->input('phone_number'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "zone_id"=>$request->input('zone_id'),
                "division_id"=>$request->input('division_id'),
                "division_name"=>$request->input('division_name'),
                "distric_id"=>$request->input('distric_id'),
                "distric_name"=>$request->input('distric_name'),
                "police_station"=>$request->input('police_station'),
                "thana_id"=>$request->input('thana_id'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "status"=>$request->input('status')
            ]);

            if($UpdateRetailer) {
                if(isset($CheckUserTable) && !empty($CheckUserTable)){
                    $UpdateUser = User::where('retailer_id',$RetailerUpdateID)
                    ->update([
                        "name"=>$request->input('retailer_name'),
                        "email"=>$request->input('retailer_name').'@waltonbd.com',
                    ]);
                }
                else {
                    $AddUser = User::create([
                        "name"=>$request->input('retailer_name'),
                        "retailer_id"=>$RetailerUpdateID,
                        "email"=>$request->input('retailer_name').'@waltonbd.com',
                        "password"=>Hash::make('phone_number@rg'),
                        "password_confirmation"=>Hash::make('phone_number@rg')
                    ]);
                }
            }
            Log::info('Existing Retailer Updated');
            return response()->json('success');
        } else {
            Log::error('Existing Retailer Updation Failed');
            return response()->json('error');
        }
    }

    public function CheckRetailer($mobile=null)
    {
        $getCurlResponse = "";

        if(isset($mobile) && $mobile !=0) {
            $getCurlResponse    = getData(sprintf(RequestApiUrl("RetailerPhone"),$mobile),"GET");
        }

        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            return response()->json($responseData);
        } else {
            return response()->json($getCurlResponse['response_data']);
        }
        
    }

    public function old_CheckRetailer($id=null,$mobile=null)
    {
        $getCurlResponse = "";

        if(isset($id) && $id !=0 || $id !=null) {
            $getCurlResponse    = getData(sprintf(RequestApiUrl("RetailerId"),$id),"GET");
        }
        elseif(isset($mobile) && $mobile !=0) {
            $getCurlResponse    = getData(sprintf(RequestApiUrl("RetailerPhone"),$mobile),"GET");
        }
        else {
            $getCurlResponse    = getData(sprintf(RequestApiUrl("RetailerId"),$id),"GET");
        }

        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            return response()->json($responseData);
        } else {
            return response()->json($getCurlResponse['response_data']);
        }    
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = retailer::find($id);
            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateRetailerStatus = retailer::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateRetailerStatus) {
                Log::info('Existing Retailer Status Changed');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Existing Retailer Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::error('Existing Retailer Id Not Found');
            return response()->json('error');
        }
    }

    public function ApiListRetailerInsert()
    {
        $getCurlResponse    = getData(RequestApiUrl("RetailerAll"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            $totalInsertRow =0;
            foreach ($responseData as $row) {
                $totalInsertRow +=1;
                $RetailerID = $row['Id'];
                $CheckRetailer = retailer::where('retailer_id',$RetailerID)->first();
                if($CheckRetailer) {
                	
                    $UpdateRetailerInfo = retailer::where('retailer_id',$RetailerID)
                    ->update([
						"retailer_id"=>$RetailerID,
						"retailer_name"=>$row['RetailerName'],
						"retailder_address"=>$row['RetailerAddress'],
						"owner_name"=>$row['OwnerName'],
						"phone_number"=>$row['PhoneNumber'],
						"payment_type"=>$row['PaymentNumberType'],
						"payment_number"=>$row['PaymentNumber'],
						"zone_id"=>$row['ZoneId'],
						"division_id"=>$row['DivisionId'],
						"division_name"=>$row['Division'],
						"distric_id"=>$row['DistrictId'],
						"distric_name"=>$row['District'],
						"police_station"=>$row['PoliceStation'],
						"thana_id"=>$row['ThanaId'],
						"distributor_code"=>$row['DistributorCode'],
						"distributor_code2"=>$row['DistributorCode2'],
						"status"=>$row['IsActive']
                    ]);    

                } else {

                    $AddRetailerInfo = retailer::create([
                        "retailer_id"=>$RetailerID,
						"retailer_name"=>$row['RetailerName'],
						"retailder_address"=>$row['RetailerAddress'],
						"owner_name"=>$row['OwnerName'],
						"phone_number"=>$row['PhoneNumber'],
						"payment_type"=>$row['PaymentNumberType'],
						"payment_number"=>$row['PaymentNumber'],
						"zone_id"=>$row['ZoneId'],
						"division_id"=>$row['DivisionId'],
						"division_name"=>$row['Division'],
						"distric_id"=>$row['DistrictId'],
						"distric_name"=>$row['District'],
						"police_station"=>$row['PoliceStation'],
						"thana_id"=>$row['ThanaId'],
						"distributor_code"=>$row['DistributorCode'],
						"distributor_code2"=>$row['DistributorCode2'],
						"status"=>$row['IsActive']
                    ]);

                }

            }
            Log::info('Retailer Information Addedd Successfully After Api Call');
            return response()->json('success');
        } else {
            Log::error('Retailer Information Addedd Failed After Api Call');
            return response()->json('error');
        }
    }

    public function destroy(Zone $zone)
    {
        //
    }

    public function retailerShopTimeEdit($retailId)
    {
        if(isset($retailId) && $retailId > 0) {
            $response = Retailer::where('id','=',$retailId)
            ->select('shop_start_time','shop_end_time')
            ->first();
            $startTime  = $response['shop_start_time'];
            $endTime    = $response['shop_end_time'];

            return response()->json(['startTime'=>$startTime,'endTime'=>$endTime]);
        }
        return response()->json('error');    
    }

    public function saveShopWorkingTime(Request $request)
    {
        $retailId   = $request->input('retailer_id');
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');

        if(isset($retailId) && $retailId > 0) {
            $success = Retailer::where('id','=',$retailId)
            ->update([
                "shop_start_time"=>$startTime,
                "shop_end_time"=>$endTime
            ]);

            if($success) {
                return response()->json('success');
            }
            return response()->json('error');
        }
    }


}
