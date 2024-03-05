<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\PreBookin;
use App\Models\Colors;
use App\Models\Products;
use App\Models\ProductMasterPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Validator;
use Mail;
use Response;

class PreBookingController extends Controller
{
    
    public function index(Request $request) 
    {
        $currentDate     = date('Y-m-d');

        $prebooking_list = PreBookin::where('start_date', '<=', $currentDate)
        ->where('end_date', '>=', $currentDate)
        ->where('status','=',1)
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $prebooking_list = DB::table('prebookings')
                ->where('id',$query)
                ->where('start_date', '<=', $currentDate)
                ->where('end_date', '>=', $currentDate)
                ->orWhere('model','like', '%'.$query.'%')
                ->orWhere('color','like', '%'.$query.'%')
                ->orWhere('start_date', 'like', '%'.$query.'%')
                ->orWhere('end_date', 'like', '%'.$query.'%')
                ->orWhere('minimum_advance_amount', 'like', '%'.$query.'%')
                ->orWhere('max_qty', 'like', '%'.$query.'%')
                ->orWhere('price', 'like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.prebooking.result_data', compact('prebooking_list'))->render();
        }

        if(isset($prebooking_list) && $prebooking_list->isNotEmpty()) {
            Log::info('Load Active Pre-Booking List');
        } else {
            Log::warning('Pre-Booking Active List Not Found');
        }

        return view('admin.prebooking.list',compact('prebooking_list'));
    }

    public function expirePreBooking(Request $request) 
    {
        $currentDate     = date('Y-m-d');

        $prebooking_list = PreBookin::where('start_date', '<', $currentDate)
        ->where('end_date', '<', $currentDate)
        //->where('status','=',1)
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $prebooking_list = DB::table('prebookings')
                ->where('id',$query)
                ->where('start_date', '<', $currentDate)
                ->where('end_date', '<', $currentDate)
                ->orWhere('model','like', '%'.$query.'%')
                ->orWhere('color','like', '%'.$query.'%')
                ->orWhere('start_date', 'like', '%'.$query.'%')
                ->orWhere('end_date', 'like', '%'.$query.'%')
                ->orWhere('minimum_advance_amount', 'like', '%'.$query.'%')
                ->orWhere('max_qty', 'like', '%'.$query.'%')
                ->orWhere('price', 'like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.prebooking.expire_result_data', compact('prebooking_list'))->render();
        }

        if(isset($prebooking_list) && $prebooking_list->isNotEmpty()) {
            Log::info('Load Expire Pre-Booking List');
        } else {
            Log::warning('Expire Pre-Booking List Not Found');
        }
        return view('admin.prebooking.expire_list',compact('prebooking_list'));
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        $rules = [
            'model'=>'required',
            'color'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'minimum_advance_amount'=>'required',
            'max_qty'=>'required',
            'price'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Pre-Booking Validation Failed');
            return response()->json([
                'fail'=>true,
                'errors'=>$validator->errors()
            ]);
        }

        $status         = 0;
        $model          = $request->input('model');
        $CheckInfo      = PreBookin::where('model',$model)->first();
        if($CheckInfo) {
            $UpdateInfo = PreBookin::where('model',$model)
            ->update([
                "model"=>$request->input('model'),
                "color"=> $request->input('color'),
                "start_date"=>$request->input('start_date'),
                "end_date"=>$request->input('end_date'),
                "minimum_advance_amount"=> $request->input('minimum_advance_amount'),
                "max_qty"=> $request->input('max_qty'),
                "price"=> $request->input('price'),
                "status"=> $request->input('status'),
                "updated_at"=> Carbon::now()
            ]);
            if($AddInfo) {
                $status = 1;
            }
            Log::info('Existing Pre-Booking Product Updated');    
        } 
        else 
        {
            $AddInfo = PreBookin::create([
                "model"=>$request->input('model'),
                "color"=> $request->input('color'),
                "start_date"=>$request->input('start_date'),
                "end_date"=>$request->input('end_date'),
                "minimum_advance_amount"=> $request->input('minimum_advance_amount'),
                "max_qty"=> $request->input('max_qty'),
                "price"=> $request->input('price'),
                "status"=> $request->input('status'),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now()
            ]);

            if($AddInfo) {
                $status = 1;
            }
            Log::info('Create Pre-Booking Product');
        }

        if($status == 1){
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $ProductInfo = PreBookin::where('id',$id)->first();

            if($ProductInfo) {
                Log::info('Get Pre-Booking Product By Id');
                return response()->json($ProductInfo); 
            } else {
                Log::warning('Pre-Booking Product Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Pre-Booking Product Id');
            return response()->json('error');
        }
    }

    
    public function update(Request $request)
    {
        $id = $request->input('update_id');
        $rules = [
            'model'=>'required',
            'color'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'minimum_advance_amount'=>'required',
            'max_qty'=>'required',
            'price'=>'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            Log::error('Pre-Booking Update Validation Failed');
            return response()->json([
                'fail'=>true,
                'errors'=>$validator->errors()
            ]);
        }

        $CheckInfo = PreBookin::where('id',$id)->first();
        if(isset($CheckInfo) && !empty($CheckInfo)) {
            $UpdateInfo = PreBookin::where('id',$id)
            ->update([
                "model"=>$request->input('model'),
                "color"=> $request->input('color'),
                "start_date"=>$request->input('start_date'),
                "end_date"=>$request->input('end_date'),
                "minimum_advance_amount"=> $request->input('minimum_advance_amount'),
                "max_qty"=> $request->input('max_qty'),
                "price"=> $request->input('price'),
                "status"=> $request->input('status'),
                "updated_at"=> Carbon::now()
            ]);

            if($UpdateInfo) {
                Log::info('Existing Pre-Booking Update Success');
                return response()->json('success');
            }
        }
        else {
            Log::error('Pre-Booking Update Failed');
            return response()->json('error');
        }
    }

    
    public function destroy($id)
    {
        //
    }

    public function ChangeStatus($id)
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = PreBookin::where('id',$id)->first();
            $old_status = $StatusInfo->status;


            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdatePreBookingStatus = PreBookin::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdatePreBookingStatus) {
                Log::info('Pre-Booking Status Update Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Pre-Booking Status Update Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Pre-Booking Product Id');
            return response()->json('error');
        }
        
    }
}
