<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rsm;
use Carbon\Carbon;
use Validator;
use DB;


class RsmController extends Controller
{
    
    
    public function index(Request $request)
    {
        $Rsmlist = GetTableWithPagination('view_rsm_delar_info',100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $Rsmlist = DB::table('view_rsm_delar_info')
                ->where('id',$query)
                ->orWhere('rsm','like', '%'.$query.'%')
                ->orWhere('asm','like', '%'.$query.'%')
                ->orWhere('email_address', 'like', '%'.$query.'%')
                ->orWhere('mobile_no', 'like', '%'.$query.'%')
                ->orWhere('zone', 'like', '%'.$query.'%')
                ->orWhere('distributor_name', 'like', '%'.$query.'%')
                ->orWhere('district', 'like', '%'.$query.'%')
                ->orWhere('code',$query)
                ->orWhere('import_code', $query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.ime.result_data', compact('Rsmlist'))->render();
        }
        return view('admin.ime.list',compact('Rsmlist'));
    }

    public function index_old_backup(Request $request)
    {
        
        //$Rsm_list = Rsm::get();
        //return response()->json($Rsm_list);

        //$Rsmlist = DB::table('view_rsm_delar_info')->get();
        //return view('admin.rsm-list',compact('Rsmlist'));

        $Rsmlist = GetTableWithPagination('view_rsm_delar_info',100);
        if ($request->ajax()) {
            return view('admin.ime.result_data', compact('Rsmlist'));
        }
        return view('admin.ime.list',compact('Rsmlist'));
    }

    
    public function create()
    {
        $Rsmlist = Rsm::get();
        return view('admin.rsm-list',compact('Rsmlist'));
    }

   
    public function store(Request $request)
    {
        $get_rsm_data = ['{"RSM":"Md.Ilias Hossain","RSM_ID":"12731","ASM":"N/A","ASM_ID":"N/A","TSO":"Abdullah","TSO_ID":"36573","Emil_Address":"manun36573@waltonbd.com","Mobile_No":"1713449181","Zone":"Barisal","Distributor_Name":"Ratan Electronics","District":"Gopalgonj","Code":"58412","Import_Code":"12510"}','{"RSM":"Md.Saidul Haq","RSM_ID":"16418","ASM":"N/A","ASM_ID":"N/A","TSO":"Abdullah","TSO_ID":"36573","Emil_Address":"manun36573@waltonbd.com","Mobile_No":"01670879104","Zone":"Dhaka","Distributor_Name":"Sayed Electronics","District":"Mymensingh","Code":"16418","Import_Code":"12510"}'];


        $JsonArray = [];
        foreach ($get_rsm_data as $row) 
        {
            $JsonArray[] = json_decode($row,true);
        }

        $Status = 0;
        foreach($JsonArray as $row)
        {

            $RsmCode = $row['Code'];

            $CheckInfo = Rsm::where('code',$RsmCode)->first();
            if($CheckInfo) {

                $UpdateData = Rsm::where('id',$CheckInfo['id'])
                ->update([
                    "rsm"=> $row['RSM'],
                    "rsm_id"=> $row['RSM_ID'],
                    "asm"=> $row['ASM'],
                    "asm_id"=> $row['ASM_ID'],
                    "tso"=> $row['TSO'],
                    "tso_id"=> $row['TSO_ID'],
                    "email_address"=> $row['Emil_Address'],
                    "mobile_no"=> $row['Mobile_No'],
                    "zone"=> $row['Zone'],
                    "distributor_name"=> $row['Distributor_Name'],
                    "district"=> $row['District'],
                    "code"=> $row['Code'],
                    "import_code"=> $row['Import_Code'],
                    "updated_at"=> Carbon::now()
                ]);

            } else {

                $AddData = Rsm::create([
                    "rsm"=> $row['RSM'],
                    "rsm_id"=> $row['RSM_ID'],
                    "asm"=> $row['ASM'],
                    "asm_id"=> $row['ASM_ID'],
                    "tso"=> $row['TSO'],
                    "tso_id"=> $row['TSO_ID'],
                    "email_address"=> $row['Emil_Address'],
                    "mobile_no"=> $row['Mobile_No'],
                    "zone"=> $row['Zone'],
                    "distributor_name"=> $row['Distributor_Name'],
                    "district"=> $row['District'],
                    "code"=> $row['Code'],
                    "import_code"=> $row['Import_Code'],
                    "created_at"=> Carbon::now(),
                    "updated_at"=> Carbon::now()
                ]); 

            }

        }
        return response()->json(["success"=>"Data Insert Successfully"]);

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
    
}
