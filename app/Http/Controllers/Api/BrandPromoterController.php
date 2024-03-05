<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\BrandPromoter;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Validator;
use Response;
use Session;

class BrandPromoterController extends Controller
{
    
    public function index(Request $request)
    {
        $CategoryList      = DB::table('bp_retailer_categories')->where('status','=',1)->get();
        $BrandPromoterList = GetTableWithPagination('brand_promoters',100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $BrandPromoterList = DB::table('brand_promoters')
                ->where('id',$query)
                ->orWhere('bp_name','like', '%'.$query.'%')
                ->orWhere('bp_phone', 'like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.bpromoter.result_data', compact('BrandPromoterList','CategoryList'))->render();
        }

        if(isset($BrandPromoterList) && $BrandPromoterList->isNotEmpty()) {
            Log::info('Load Brand Promoter List');
        } else {
            Log::warning('Brand Promoter List Not Found');
        }
        return view('admin.bpromoter.list',compact('BrandPromoterList','CategoryList'));
    }


    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {        
        $rules = [
            'bp_name'=>'required',
            'bp_phone'=>'required|digits:11|numeric|unique:brand_promoters',
            'retailer_name'=>'required',
            'owner_name'=>'required',
            'retailer_phone_number'=>'required|digits:11|numeric',
            'retailder_address'=>'required',
            'distributor_code'=>'required',
            'distributor_code2'=>'required',
            'distributor_name'=>'required',
            'distributor_zone'=>'required',
            'payment_type'=>'required',
            'payment_number'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Brand Promoter Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $bp_id              = $request->input('bp_id');
        $bp_name            = $request->input('bp_name');
        $bp_phone           = $request->input('bp_phone');
        $CheckPromoter      = BrandPromoter::where('bp_phone',$bp_phone)->first();

    
        if($CheckPromoter)
        {
            $CheckUserTable     = User::where('bp_id',$CheckPromoter['id'])->first();
            $UpdatePromoter = BrandPromoter::where('id',$CheckPromoter['id'])
            ->update([
                "category_id"=>$request->input('bp_category'),
                "bp_id"=>$request->input('bp_id'),
                "retailer_id"=>$request->input('retailer_id'),
                "bp_name"=>$request->input('bp_name'),
                "bp_phone"=>$request->input('bp_phone'),
                "retailer_name"=>$request->input('retailer_name'),
                "owner_name"=>$request->input('owner_name'),
                "police_station"=>$request->input('police_station'),
                "retailer_phone_number"=>$request->input('retailer_phone_number'),
                "retailder_address"=>$request->input('retailder_address'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "distributor_name"=>$request->input('distributor_name'),
                "distributor_zone"=>$request->input('distributor_zone'),
                "division_name"=>$request->input('division_name'),
                "distric_name"=>$request->input('distric_name'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "status"=>$request->input('status')
            ]);

            if($UpdatePromoter) {
                if(isset($CheckUserTable) && !empty($CheckUserTable)){
                    $UpdateUser = User::where('bp_id',$CheckPromoter['id'])
                    ->update([
                        "name"=>$request->input('bp_name'),
                        "email"=>$request->input('bp_name').'@waltonbd.com'
                    ]);
                }
            }
            Log::info('Existing Brand Promoter Updated SuccessFully');
            return response()->json('success');
        } 
        else 
        {
            $AddPromoter = BrandPromoter::create([
                "category_id"=>$request->input('bp_category'),
                "bp_id"=>$request->input('bp_id'),
                "retailer_id"=>$request->input('retailer_id'),
                "bp_name"=>$request->input('bp_name'),
                "bp_phone"=>$request->input('bp_phone'),
                "retailer_name"=>$request->input('retailer_name'),
                "owner_name"=>$request->input('owner_name'),
                "police_station"=>$request->input('police_station'),
                "retailer_phone_number"=>$request->input('retailer_phone_number'),
                "retailder_address"=>$request->input('retailder_address'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "distributor_name"=>$request->input('distributor_name'),
                "distributor_zone"=>$request->input('distributor_zone'),
                "division_name"=>$request->input('division_name'),
                "distric_name"=>$request->input('distric_name'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "status"=>$request->input('status')
            ]);

            $id = DB::getPdo()->lastInsertId();
            if($AddPromoter) {
                $AddUser = User::create([
                    "name"=>$request->input('bp_name'),
                    "bp_id"=>$id,
                    "email"=>$request->input('bp_name').'@waltonbd.com',
                    "password"=>Hash::make('bp_phone@rg'),
                    "password_confirmation"=>Hash::make('bp_phone@rg')
                ]);
            }
            Log::info('Create Brand Promoter SuccessFully');
            return response()->json('success');
        }
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        if(isset($id) && $id>0) {
            $BPromoterInfo = DB::table('view_brand_promoter_list')
            ->where('id',$id)
            ->first();

            if($BPromoterInfo) {
                Log::info('Get Brand Promoter By Id');
                return response()->json($BPromoterInfo); 
            } else {
                Log::warning('Brand Promoter Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Brand Promoter Id');
            return response()->json('error');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            //'bp_id'=>'required',
            //'retailer_id'=>'required',
            'bp_name'=>'required',
            'bp_phone'=>'required|digits:11|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Brand Promoter Update Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $BPromoterUpdateID      = $request->input('update_id');
        $bp_id                  = $request->input('bp_id');
        $retailer_id            = $request->input('retailer_id');
        $bp_name                = $request->input('bp_name');
        $bp_phone               = $request->input('bp_phone');
        $category_id            = $request->input('bp_category');
        $CheckBPromoter         = BrandPromoter::where('bp_phone',$bp_phone)
        ->where('id','<>',$BPromoterUpdateID)
        ->first();
        

        if(!$CheckBPromoter) {
            $CheckUserTable  = User::where('bp_id',$BPromoterUpdateID)->first();
            
            $UpdateBPromoter = BrandPromoter::where('id',$BPromoterUpdateID)
            ->update([
                "category_id"=>$category_id ? $category_id:0,
                "bp_id"=>$bp_id ? $bp_id:0,
                "retailer_id"=>$retailer_id ? $retailer_id:0,
                "bp_name"=>$bp_name,
                "bp_phone"=>$bp_phone,
                "retailer_name"=>$request->input('retailer_name'),
                "owner_name"=>$request->input('owner_name'),
                "police_station"=>$request->input('police_station'),
                "retailer_phone_number"=>$request->input('retailer_phone_number'),
                "retailder_address"=>$request->input('retailder_address'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "distributor_name"=>$request->input('distributor_name'),
                "distributor_zone"=>$request->input('distributor_zone'),
                "division_name"=>$request->input('division_name'),
                "distric_name"=>$request->input('distric_name'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "status"=>$request->input('status')
            ]);

            if($UpdateBPromoter) {
                if(isset($CheckUserTable) && !empty($CheckUserTable)){
                    $UpdateUser = User::where('bp_id',$BPromoterUpdateID)
                    ->update([
                        "name"=>$request->input('bp_name'),
                        "email"=>$request->input('bp_name').'@waltonbd.com',
                    ]);
                }
                else {
                    $AddUser = User::create([
                        "name"=>$request->input('bp_name'),
                        "bp_id"=>$BPromoterUpdateID,
                        "email"=>$request->input('bp_name').'@waltonbd.com',
                        "password"=>Hash::make('bp_phone@rg'),
                        "password_confirmation"=>Hash::make('bp_phone@rg')
                    ]);
                }
            }
            Log::info('Existing Brand Promoter Update SuccessFully');
            return response()->json('success');
        } else {
            Log::error('Existing Brand Promoter Updated Failed');
            return response()->json('error');
        }     
    }

    public function CheckBPromoterFromApi($phone)
    {
        $getCurlResponse    = getData(sprintf(RequestApiUrl("BPromoterPhone"),$phone),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            return response()->json($responseData);
        } else {
            //return response()->json($getCurlResponse['response_data']);
            return response()->json('error');
        }
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = BrandPromoter::find($id);
            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateBrandPromoterStatus = BrandPromoter::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateBrandPromoterStatus) {
                Log::info('Brand Promoter Status Changed Success');
                return response()->json(['success'=>'Status change success.']);
            } else {
                Log::error('Brand Promoter Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Brand Promoter Id');
            return response()->json('error');
        }
    }

    public function AddBPromoterFromApi()
    {
        $getCurlResponse    = getData(RequestApiUrl("BrandPromoterAll"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            $totalInsertRow =0;
            foreach ($responseData as $row) {
                $totalInsertRow +=1;
                $BPromoterID = $row['Id'];
                $CheckPromoter = BrandPromoter::where('promoter_id',$PromoterID)->first();
                if($CheckPromoter) {
                    $UpdateBPromoterInfo = BrandPromoter::where('promoter_id',$ZoneID)
                    ->update([
                        "bp_id"=>$BPromoterID,
                        "retailer_id"=>$row['BPReId'],
                        "bp_name"=>$row['BPName'],
                        "bp_phone"=>$row['BPPhone']
                    ]);    

                } else {
                    $AddBPromoterInfo = zone::create([
                        "bp_id"=>$BPromoterID,
                        "retailer_id"=>$row['BPReId'],
                        "bp_name"=>$row['BPName'],
                        "bp_phone"=>$row['BPPhone']
                    ]);
                }

            }
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function destroy($id)
    {
        //
    }
    
    public function focus_model_to_bp()
    {
        //Session::forget('catId');
        $productModelLists = Products::select('product_master_id','product_id','product_model')
        ->where('status',1)
        ->whereNotNull('product_id')
        //->whereIn('product_master_id',[1,2,3,4,5,6,7,8,9,10])
        ->groupBy('product_model')
        ->get();

        $catId = 1;
        //Session::put('catId', $catId);
        return view('admin.bpromoter.focus_model_to_bp',compact('productModelLists','catId'));
    }

    public function focus_model_to_bp_by_cat($catId)
    {
        //Session::forget('catId');
        //$catId = 2;
        Session::put('catId', $catId);
        return response()->json(['catId'=>$catId]);
    }

    public function focus_model_to_bp_save(Request $request)
    {
       //dd($request->all());
       $category_id         = $request->input('category_id');
       $select_model        = $request->input('select_model');

       $saveProductIdLists  = DB::table('bp_model_stocks')
        ->select('product_master_id')
        ->where('bp_category_id',$category_id)
        ->get();

        $productIdLists = [];
        if($saveProductIdLists->isNotEmpty()){
            foreach($saveProductIdLists as $val) {
                $productIdLists[] = $val->product_master_id;
            }
        }

        $getDiffVal = array_diff($productIdLists, $select_model);

        $status = 0;
        if(is_array($select_model) && $category_id > 0) 
        {
            foreach($select_model as $key=>$model) 
            {
                $checkStatus = DB::table('bp_model_stocks')
                ->where('bp_category_id',$category_id)
                ->where('product_master_id',$request->input('product_master_id')[$model])
                ->where('product_id',$request->input('product_id')[$model])
                ->where('model_name','like','%'.$request->input('model_name')[$model].'%')
                ->first();

                if($checkStatus) 
                {
                    $modelStockSaveByBp = DB::table('bp_model_stocks')
                    ->where('bp_category_id',$category_id)
                    ->where('product_master_id',$request->input('product_master_id')[$model])
                    ->where('model_name','like','%'.$request->input('model_name')[$model].'%')
                    ->update([
                        "bp_category_id"=>$category_id,
                        "product_master_id"=> $request->input('product_master_id')[$model],
                        "product_id"=> $request->input('product_id')[$model],
                        "model_name"=> $request->input('model_name')[$model],
                        "green"=> $request->input('green')[$model],
                        "yellow"=> $request->input('yellow')[$model],
                        "red"=> $request->input('red')[$model],
                        "updated_at"=>Carbon::now()
                    ]);

                    if($modelStockSaveByBp) {
                        $status = 1;
                    }
                } 
                else 
                {
                    $modelStockSaveByBp = DB::table('bp_model_stocks')
                    ->insert([
                        "bp_category_id"=>$category_id,
                        "product_master_id"=> $request->input('product_master_id')[$model],
                        "product_id"=> $request->input('product_id')[$model],
                        "model_name"=> $request->input('model_name')[$model],
                        "green"=> $request->input('green')[$model],
                        "yellow"=> $request->input('yellow')[$model],
                        "red"=> $request->input('red')[$model],
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                    if($modelStockSaveByBp) {
                        $status = 1;
                    }
                }
            }
        }

        if(!empty($getDiffVal)) {
            foreach($getDiffVal as $diffId) {
                $modelRemoveByBp = DB::table('bp_model_stocks')
                ->where('bp_category_id',$category_id)
                ->where('product_master_id',$diffId)
                ->delete();
                if($modelRemoveByBp) {
                    $status = 1;
                }
            }
        }

        if($status == 1) {
            Session::put('catId', $category_id);
            return redirect()->back()->with('success','Stock Assigned Successfully');
        }
        return redirect()->back()->with('error','Stock Assigned Faield');
    }
}
