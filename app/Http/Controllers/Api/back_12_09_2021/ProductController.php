<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductMasterPrice;
use App\Models\ProductChangeLog;
use DB;
use Validator;
use Carbon\Carbon;
use Response;

class ProductController extends Controller
{
    
    public function index(Request $request) 
    {
        $product_list = GetTableWithPagination('view_product_master',100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $product_list = DB::table('view_product_master')
                ->where('product_master_id',$query)
                ->orWhere('product_type','like', '%'.$query.'%')
                ->orWhere('product_model','like', '%'.$query.'%')
                ->orWhere('mrp_price', 'like', '%'.$query.'%')
                ->orWhere('msdp_price', 'like', '%'.$query.'%')
                ->orWhere('msrp_price', 'like', '%'.$query.'%')
                ->orWhere('category2', 'like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.product.result_data', compact('product_list'))->render();
        }

        if(isset($product_list) && $product_list->isNotEmpty()) {
            Log::info('Load Product List');
        } else {
            Log::warning('Product List Not Found');
        }
        return view('admin.product.list',compact('product_list'));
    }

    public function create() 
    {
        
    }

    
    public function store(Request $request)
    {
        $rules = [
            'product_id'=>'required',
            //'product_code'=>'required',
            'product_model'=>'required',
            'mrp_price'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Create Product Validation Failed');
            return response()->json([
                'fail'=>true,
                'errors'=>$validator->errors()
            ]);
        }

        $status         = 0;
        $product_id     = $request->input('product_id');

        if($product_id !=null || !empty($product_id)) {
            $CheckInfo      = Products::where('product_id',$product_id)->first();
            if(!empty($CheckInfo)) 
            {
                $UpdateProductInfo = Products::where('product_id',$product_id)
                ->update([
                    "product_id"=>$request->input('product_id'),
                    "product_code"=> $request->input('product_code'),
                    "product_type"=>$request->input('product_type'),
                    "product_model"=>$request->input('product_model'),
                    "category2"=> $request->input('category'),
                    "updated_at"=> Carbon::now()
                ]);

                $CheckProductPrice  = ProductMasterPrice::where('product_id',$product_id)->first();

                if($CheckProductPrice) {
                    $UpdateProductPrice = ProductMasterPrice::where('product_id',$product_id)
                    ->update([
                        "mrp_price"=>$request->input('mrp_price'),
                        "msdp_price"=>$request->input('msdp_price'),
                        "msrp_price"=>$request->input('msrp_price'),
                        "updated_at"=> Carbon::now()
                    ]);
                } else {

                    $AddProductPrice = ProductMasterPrice::create([
                        "product_id"=>$request->input('product_id'),
                        "mrp_price"=>$request->input('mrp_price'),
                        "msdp_price"=>$request->input('msdp_price'),
                        "msrp_price"=>$request->input('msrp_price'),
                        "created_at"=> Carbon::now(),
                        "updated_at"=> Carbon::now()
                    ]);
                }
                $status = 1;
                Log::info('Existing Product Updated');    
            } 
        }        
        else 
        {
           $AddProductInfo = Products::create([
                "product_id"=> $request->input('product_id'),
                "product_code"=> $request->input('product_code'),
                "product_type"=> $request->input('product_type'),
                "product_model"=> $request->input('product_model'),
                "category2"=> $request->input('category'),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now()
            ]);

            $AddProductPrice = ProductMasterPrice::create([
                "product_id"=>$request->input('product_id'),
                "mrp_price"=>$request->input('mrp_price'),
                "msdp_price"=>$request->input('msdp_price'),
                "msrp_price"=>$request->input('msrp_price'),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now()
            ]);
            $status = 1; 
            Log::info('Create Product Successfully');
            return response()->json('success');
        }

        if($status == 0) {
            return response()->json('error');
        } else {
            return response()->json('success');
        }
    }

    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        if(isset($id) && $id>0) {
            $ProductInfo = DB::table('view_product_master')
            ->where('product_master_id',$id)
            ->first();

            if($ProductInfo) {
                Log::info('Get Product By Id');
                return response()->json($ProductInfo); 
            } else {
                Log::warning('Product Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Product Id');
            return response()->json('error');
        }
    }

    
    public function update(Request $request, $id)
    {
        $id = $request->input('product_master_id');

        $rules = [
            'product_id'=>'required',
            //'product_code'=>'required',
            'product_model'=>'required',
            'mrp_price'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Product Validation Failed');
            return response()->json([
                'fail'=>true,
                'errors'=>$validator->errors()
            ]);
        }

        

        $CheckProduct = Products::where('product_master_id',$id)->first();
        if(isset($CheckProduct) && !empty($CheckProduct)) {
            $status = 0;
            $UpdateProductInfo = Products::where('product_master_id',$id)
            ->update([
                "product_id"=> $request->input('product_id'),
                "product_code"=>  $request->input('product_code'),
                "product_type"=> $request->input('product_type'),
                "product_model"=> $request->input('product_model'),
                "category2"=> $request->input('category'),
                "updated_at"=> Carbon::now()
            ]);

            if($UpdateProductInfo) {
                $CheckProductPrice = ProductMasterPrice::where('product_id',$request->input('product_id'))->first();

                if(isset($CheckProductPrice) && !empty($CheckProductPrice)) {
                    $UpdateProductPrice = ProductMasterPrice::where('product_id',$request->input('product_id'))
                    ->update([
                        "product_id"=> $request->input('product_id'),
                        "mrp_price"=>  $request->input('mrp_price'),
                        "msdp_price"=>  $request->input('msdp_price'),
                        "msrp_price"=>  $request->input('msrp_price'),
                        "updated_at"=> Carbon::now()
                    ]);

                    $productChangeLog = ProductChangeLog::create([
                        "product_id"=>$request->input('product_id'),
                        "old_mrp_price"=>$CheckProductPrice['mrp_price'],
                        "old_msdp_price"=>$CheckProductPrice['msdp_price'],
                        "old_msrp_price"=>$CheckProductPrice['msrp_price'],
                        "new_mrp_price"=>$request->input('mrp_price'),
                        "new_msdp_price"=>$request->input('msdp_price'),
                        "new_msrp_price"=>$request->input('msrp_price'),
                        "updated_at"=> Carbon::now()
                    ]);
                    Log::info('Existing Product Updated');
                    $status = 1; 
                } 
                else 
                {
                    $AddProductPrice = ProductMasterPrice::create([
                        "product_id"=>$request->input('product_id'),
                        "mrp_price"=>$request->input('mrp_price'),
                        "msdp_price"=>$request->input('msdp_price'),
                        "msrp_price"=>$request->input('msrp_price'),
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                    $productChangeLog = ProductChangeLog::create([
                        "product_id"=>$request->input('product_id'),
                        "old_mrp_price"=>$request->input('mrp_price'),
                        "old_msdp_price"=>$request->input('msdp_price'),
                        "old_msrp_price"=>$request->input('msrp_price'),
                        "new_mrp_price"=>$request->input('mrp_price'),
                        "new_msdp_price"=>$request->input('msdp_price'),
                        "new_msrp_price"=>$request->input('msrp_price'),
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);
                    Log::info('Create New Product');
                    $status = 1; 
                }
            }

            if($status == 1) {
                Log::info('Existing Product Updated');
                return response()->json('success');
            } else {
                Log::error('Existing Product Updated Failed');
                return response()->json('error');
            }
        }
        else {
            Log::error('Existing Product Updated Failed');
            return response()->json('error');
        }
    }

    public function CheckProduct($id)
    {
        $get_id = str_replace(" ","%20",$id);
        $getCurlResponse    = getData(sprintf(RequestApiUrl("ProducId"),$get_id),"GET");
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
            $StatusInfo = Products::where('product_master_id',$id)->first();
            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $UpdateDealerStatus = Products::where('product_master_id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateDealerStatus) {
                Log::info('Existing Product Status Change Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::warning('Existing Product Status Change Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Product Id');
            return response()->json('error');
        }
    }

    public function destroy($id)
    {
        //
    }
    
    public function productStockEdit($productId)
    {
        if(isset($productId) && $productId > 0) {
            $success = Products::where('product_master_id',$productId)
            ->select('default_qty','yeallow_qty','red_qty')
            ->first();

            if($success) {
                return response()->json($success);
            }
            return response()->json('error');
        }
    }

    public function saveProductStockMaintain(Request $request)
    {
        $productId      = $request->input('product_id');
        $default_qty    = $request->input('default_qty');
        $yeallow_qty    = $request->input('yeallow_qty');
        $red_qty        = $request->input('red_qty');

        if(isset($productId) && $productId !=null || $productId > 0) {
            $success = Products::where('product_master_id',$productId)
            ->update([
                "default_qty"=>$default_qty,
                "yeallow_qty"=>$yeallow_qty,
                "red_qty"=>$red_qty,
                "updated_at"=>Carbon::now()
            ]);

            if($success) {
                return response()->json('success');
            }
        }
        return response()->json('error');
    }

    public function ApiListProductInsert()
    {
        $getCurlResponse    = getData(RequestApiUrl("ProductAll"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            $totalInsertRow =0;
            foreach ($responseData as $row) {
                $totalInsertRow +=1;
                $ProductID = $row['ProductID'];
                $CheckProduct = Products::where('product_id',$ProductID)->first();
                if($CheckProduct) {
                    $UpdateProductInfo = Products::where('product_id',$ProductID)
                    ->update([
                        "product_id"=>$ProductID,
                        //"product_code"=> $row['ProductCode'] ? $row['ProductCode']:0,
                        //"product_type"=>$row['ProductType'] ? $row['ProductType']:null,
                        "product_model"=>$row['Model'],
                        //"category2"=> $row['Category'] ? $row['Category']:null,
                        "updated_at"=> Carbon::now()
                    ]);

                    $CheckProductPrice  = ProductMasterPrice::where('product_id',$ProductID)->first();

                    if($CheckProductPrice) {
                        $UpdateProductPrice = ProductMasterPrice::where('product_id',$ProductID)
                        ->update([
                            "mrp_price"=>$row['Price'],
                            "msdp_price"=>$row['MSDP'],
                            "msrp_price"=>$row['MSRP'],
                            "updated_at"=> Carbon::now()
                        ]);
                    } else {

                        $AddProductPrice = ProductMasterPrice::create([
                            "product_id"=>$ProductID,
                            "mrp_price"=>$row['Price'],
                            "msdp_price"=>$row['MSDP'],
                            "msrp_price"=>$row['MSRP'],
                            "created_at"=> Carbon::now(),
                            "updated_at"=> Carbon::now()
                        ]);

                    }    

                } else {

                    $AddProductInfo = Products::create([
                        "product_id"=>$ProductID,
                        //"product_code"=> $row['ProductCode'] ? $row['ProductCode']:0,
                        //"product_type"=>$row['ProductType'] ? $row['ProductType']:null,
                        "product_model"=>$row['Model'],
                        //"category2"=> $row['Category'] ? $row['Category']:null,
                        "created_at"=> Carbon::now(),
                        "updated_at"=> Carbon::now()
                    ]);

                    $AddProductPrice = ProductMasterPrice::create([
                        "product_id"=>$ProductID,
                        "mrp_price"=>$row['Price'],
                        "msdp_price"=>$row['MSDP'],
                        "msrp_price"=>$row['MSRP'],
                        "created_at"=> Carbon::now(),
                        "updated_at"=> Carbon::now()
                    ]); 

                }

            }
            //return response()->json(['status'=>200,'totalRow'=>$totalInsertRow]);
            Log::info('Product Insert Successfully From Api');
            return response()->json('success');
        } else {
            Log::error('Product Insert Error From Api');
            return response()->json('error');
        }
    }
}
