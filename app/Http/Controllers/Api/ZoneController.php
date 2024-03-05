<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Validator;
use Response;

class ZoneController extends Controller
{
    
    public function index(Request $request)
    {
        $ZoneList = GetTableWithPagination('view_zone_list',100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $ZoneList = DB::table('view_zone_list')
                ->where('id',$query)
                ->orWhere('zone_name','like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.zone.result_data', compact('ZoneList'))->render();
        }
        Log::info('Load Zone List');
        return view('admin.zone.list',compact('ZoneList'));
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {        
        $rules = [
            'zone_name'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return Response::json(['errors' => $validator->errors()]);

        $zone_id        = $request->input('zone_id');
        $zone_name      = $request->input('zone_name');
        $CheckZone      = zone::where('zone_name',$zone_name)->first();

        if($CheckZone) {
            $UpdateZone = zone::where('id',$CheckZone['id'])
            ->update([
                "zone_id"=>$zone_id,
                "zone_name"=>$zone_name,
                "status"=>$request->input('status'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ]);
            Log::info('Update Existing Zone');
            return response()->json('success');
        } else {
            $AddZone = zone::create([
                "zone_id"=>$zone_id,
                "zone_name"=>$zone_name,
                "status"=>$request->input('status')
            ]);
            Log::info('Create New Zone');
            return response()->json('success');
        }
        Log::warning('Create New Zone');
        return response()->json('error');
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $ZoneInfo = DB::table('view_zone_list')
            ->where('id',$id)
            ->first();
            Log::info('Get Zone Information By Id');
            return response()->json($ZoneInfo);
        } else {
            Log::warning('Edit Zone id is Missing');
            return response()->json('error');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'zone_name'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return Response::json(['errors' => $validator->errors()]);

        $ZoneUpdateID    = $request->input('update_id');
        $zone_id         = $request->input('zone_id');
        $zone_name       = $request->input('zone_name');

        $UpdateZone = zone::where('id',$ZoneUpdateID)
        ->update([
            "zone_id"=>$zone_id,
            "zone_name"=>$zone_name,
            "status"=>$request->input('status'),
            'updated_at'=> Carbon::now()
        ]);

        if($UpdateZone){
            Log::info('Zone Updated Successfully');
            return response()->json('success');
        } else {
            Log::error('Zone Updated Failed');
            return response()->json('error');
        } 
    }

    public function CheckZone($id)
    {
        $getCurlResponse    = getData(sprintf(RequestApiUrl("ZoneId"),$id),"GET");
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
            $StatusInfo = zone::find($id);
            $old_status = $StatusInfo->status;


            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateZoneStatus = zone::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateZoneStatus) {
                Log::info('Zone Status Updated Successfully');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Zone Status Updated Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Zone id is Missing When Status Changed');
            return response()->json('error');
        } 
    }

    public function ApiListZoneInsert()
    {
        $getCurlResponse    = getData(RequestApiUrl("ZoneAll"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            $totalInsertRow =0;
            foreach ($responseData as $row) {
                $totalInsertRow +=1;
                $ZoneID = $row['Id'];
                $CheckZone = zone::where('zone_id',$ZoneID)->first();
                if($CheckZone) {
                    $UpdateZoneInfo = zone::where('zone_id',$ZoneID)
                    ->update([
                        "zone_id"=>$ZoneID,
                        "zone_name"=>$row['ZoneName'],
                    ]);    

                } else {

                    $AddZoneInfo = zone::create([
                        "zone_id"=>$ZoneID,
                        "zone_name"=>$row['ZoneName']
                    ]);

                }

            }
            Log::info('Zone Search By Api Success');
            return response()->json('success');
        } else {
            Log::error('Zone Search By Api Failed');
            return response()->json('error');
        }
    }

    public function destroy(Zone $zone)
    {
        //
    }
}
