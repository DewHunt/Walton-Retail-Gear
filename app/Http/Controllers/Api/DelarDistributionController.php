<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DelarDistribution;
use App\Models\Products;
use App\Models\Colors;
use DB;
use Validator;

class DelarDistributionController extends Controller
{
    
    public function index()
    {
        
        $dealer_distribution_list = DelarDistribution::get();
        //echo "<pre>";print_r($dealer_distribution_list);echo "</pre>";die();
        return response()->json($dealer_distribution_list);
        //return view('admin.employee.add');
    }

    public function create()
    {        

    }

    public function store(Request $request)
    {        

        $startDate          = "2021-01-01";
        $endDate            = "2021-01-31";
        $getCurlResponse    = getData(sprintf(RequestApiUrl("DealerDistribution"),$startDate,$endDate),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            
            $Status = 0;
            foreach ($responseData as $row) 
            {
                
                $ProductID = $row['ProductID'];
                $ColorName = $row['Color'];

                $Insert_ProductId = "";
                $Insert_ColorId   = "";


                $CheckProduct = Products::where('product_id',$ProductID)->first();
                if($CheckProduct) {
                    $Insert_ProductId = $CheckProduct['product_master_id'];
                } else {

                    $AddProduct = Products::create([
                        "product_id"=>$ProductID,
                        "product_code"=>"10011455",
                        "product_type"=> "Cell Phone",
                        "product_model"=> $row['Model'],
                        "category2"=> "Smart",//Ex:Feature/Smart/Tablet/Power Bank
                    ]);

                    if($AddProduct) {
                        $last_insert_id = DB::getPdo()->lastInsertId();
                        $Insert_ProductId = $last_insert_id;
                    }

                }


                $CheckColor = Colors::where('name',$ColorName)->first();
                if($CheckColor) {
                    $Insert_ColorId = $CheckColor['color_id'];
                } else {
                    //echo "This is new Product"."<br/>";
                    $AddColor = Colors::create([
                        "name"=>$ColorName,
                    ]);

                    if($AddColor) {
                        $last_insert_id = DB::getPdo()->lastInsertId();
                        $Insert_ColorId = $last_insert_id;
                    }

                }

                $CheckDistribution = DelarDistribution::where('barcode',$row['BarCode'])->first();
                if($CheckDistribution) {
                    $Status = 0;
                } else {

                    $AddDelarDistribution = new DelarDistribution();

                    $AddDelarDistribution->barcode              = $row['BarCode'];
                    $AddDelarDistribution->barcode2             = $row['BarCode2'];
                    $AddDelarDistribution->dealer_code          = $row['DealerCode'];
                    $AddDelarDistribution->distribution_date    = $row['DistributionDate'];
                    $AddDelarDistribution->product_master_id    = $Insert_ProductId;
                    $AddDelarDistribution->color_id             = $Insert_ColorId;

                    $AddDelarDistribution->save();

                    $Status = 1;

                }

            }

            if($Status == 1) {
                return response()->json(["success"=>"Product Insert Successfully"]);
            } else {
                return response()->json(["error"=>"Data All Ready Taken...."]);
            }


        } else {
            return response()->json(["error"=>$getCurlResponse['response_data']]);
        }

    }
    
    public function store_static_test_purpose(Request $request)
    {
        
        $get_distribution_data = ['{"Model":"Olvio IPHONE10","color":"Black","BarCode":"35187311112225","BarCode2":"351873112512021","DealerCode":"12345","ProductID":"{5B7451EF-2183-45DC-8D8D-693E1321F2030}","distributiondate":"2021-01-25"}','{"Model":"Olvio V19","color":"Heart","BarCode":"351096911512345","BarCode2":"351873111554321","DealerCode":"54321","ProductID":"{5B7451EF-2183-45DC-8D8D-693E13280F7SA1631}","distributiondate":"2020-01-15"}'];


        $JsonArray = [];
        foreach ($get_distribution_data as $row) 
        {
            $JsonArray[] = json_decode($row,true);
        }

        $Status = 0;

        foreach($JsonArray as $row)
        {

            $ProductID = $row['ProductID'];
            $ColorName = $row['color'];

            $Insert_ProductId = "";
            $Insert_ColorId   = "";


            $CheckProduct = Products::where('product_id',$ProductID)->first();
            if($CheckProduct) {
                $Insert_ProductId = $CheckProduct['product_master_id'];
            } else {

                $AddProduct = Products::create([
                    "product_id"=>$ProductID,
                    "product_code"=>"10011455",
                    "product_type"=> "Cell Phone",
                    "product_model"=> $row['Model'],
                    "category2"=> "Smart",//Ex:Feature/Smart/Tablet/Power Bank
                ]);

                if($AddProduct)
                {
                    $last_insert_id = DB::getPdo()->lastInsertId();
                    $Insert_ProductId = $last_insert_id;
                }

            }


            $CheckColor = Colors::where('name',$ColorName)->first();
            if($CheckColor) {
                $Insert_ColorId = $CheckColor['color_id'];
            } else {
                //echo "This is new Product"."<br/>";
                $AddColor = Colors::create([
                    "name"=>$ColorName,
                ]);

                if($AddColor)
                {
                    $last_insert_id = DB::getPdo()->lastInsertId();
                    $Insert_ColorId = $last_insert_id;
                }
            }


            $AddDelarDistribution = new DelarDistribution();

            $AddDelarDistribution->barcode              = $row['BarCode'];
            $AddDelarDistribution->barcode2             = $row['BarCode2'];
            $AddDelarDistribution->dealer_code          = $row['DealerCode'];
            $AddDelarDistribution->distribution_date    = $row['distributiondate'];
            $AddDelarDistribution->product_master_id    = $Insert_ProductId;
            $AddDelarDistribution->color_id             = $Insert_ColorId;

            $AddDelarDistribution->save();

            

            if($AddDelarDistribution->save())
            {
                $Status = 1;
            } else {

                $Status = 0;
            }

        }

        if($Status == 1)
        {
            return response()->json(["success"=>"Product Insert Successfully"]);
        } else {

            return response()->json(["error"=>"Something Went Wrong.Please Try Again."]);
        }
               

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
