<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Distributor;
use Carbon\Carbon;
use Validator;
use DB;

class DistributorController extends Controller
{
    
    public function index()
    {
        
        $distributor_list = Distributor::get();
        echo "<pre>";print_r($distributor_list);echo "</pre>";die();
        return response()->json($distributor_list);
    }

    
    public function create()
    {
        
    }

    
    public function store(Request $request)
    {
        
        //$get_distributor_data =['{"DigitechCode":"58412","Zone":"Madaripur","ImportCode":"12510"}'];

        $get_josn_data = ['{
            "DigitechCode":"58412",
            "Zone":"Madaripur",
            "ImportCode":"12510"
        }'];


        $JsonArray = [];
        foreach ($get_josn_data as $row) 
        {
            $JsonArray[] = json_decode($row,true);
        }

        $Status = 0;
        foreach($JsonArray as $row)
        {

            $DigitechCode = $row['DigitechCode'];

            $CheckInfo = Distributor::where('digitech_code',$DigitechCode)->first();
            if($CheckInfo) {

                $UpdateData = Distributor::where('id',$CheckInfo['id'])
                ->update([
                    "digitech_code"=> $row['DigitechCode'],
                    "zone"=> $row['Zone'],
                    "import_code"=> $row['ImportCode'],
                    "updated_at"=> Carbon::now()
                ]);

            } else {

                $AddData = Distributor::create([
                   "digitech_code"=> $row['DigitechCode'],
                    "zone"=> $row['Zone'],
                    "import_code"=> $row['ImportCode'],
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
