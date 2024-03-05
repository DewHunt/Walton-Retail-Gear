<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Carbon\Carbon;
use DB;
use Response;
use Mail;
class CronjobController extends Controller
{
    
    public function index()
    {
        $clientType = "emp";
        $empIdList  = Employee::select('employee_id')
        ->where('status',1)
        ->whereNotNull('email')
        ->whereNotNull('employee_id')
        ->get();

        $ProductInfo = [];
        if(isset($empIdList) && $empIdList->isNotEmpty()) 
        {
            foreach($empIdList as $erow) 
            {
                $empId             = $erow->employee_id;
                $getCurlResponse   = getData(sprintf(RequestApiUrl("GetStock"),$empId,$clientType),"GET");
                $responseData      = json_decode($getCurlResponse['response_data'],true);

                
                if(isset($responseData) && !empty($responseData)) 
                {
                    foreach($responseData as $key=>$row) 
                    {
                        $getStockInfo = checkBPFocusModelStock($row['Model']);

                        if(isset($getStockInfo) && !empty($getStockInfo)) {
                            if($getStockInfo->green !=null && $getStockInfo->yellow !=null && $getStockInfo->red !=null ) {
                                if($row['StockQuantity'] >= $getStockInfo->yellow && $row['StockQuantity'] < $getStockInfo->green) {
                                    //echo "Mail Send";
                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'yellow'
                                    ];
                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                                elseif($row['StockQuantity'] < $getStockInfo->yellow && $row['StockQuantity'] >= $getStockInfo->red) {
                                    //echo "Mail Send";

                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'red'
                                    ];

                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                            } 
                            else {
                                if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
                                    //echo "Mail Send";

                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'yellow'
                                    ];


                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                                elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {
                                    //echo "Mail Send";

                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'red'
                                    ];

                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                            }
                        }
                        else 
                        {
                            if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {

                                $ProductInfo[$row['EmpId']][] = [
                                    'empEmail'=>$erow->email,
                                    'DealerName' =>$row['DealerName'],
                                    "DealerCode"=>$row['DealerCode'],
                                    'DealerPhone'=>$row['DealerPhone'],
                                    'RetailerName'=>$row['RetailerName'],
                                    'RetailerPhone'=>$row['RetailerPhone'],
                                    'Model'=>$row['Model'],
                                    'AvailableQty'=>$row['StockQuantity'],
                                    'Status'=>'yellow'
                                ];


                                //$this->stockReportSendMail($erow->email,$data);
                            }
                            elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {

                                $ProductInfo[$row['EmpId']][] = [
                                    'empEmail'=>$erow->email,
                                    'DealerName' =>$row['DealerName'],
                                    "DealerCode"=>$row['DealerCode'],
                                    'DealerPhone'=>$row['DealerPhone'],
                                    'RetailerName'=>$row['RetailerName'],
                                    'RetailerPhone'=>$row['RetailerPhone'],
                                    'Model'=>$row['Model'],
                                    'AvailableQty'=>$row['StockQuantity'],
                                    'Status'=>'red'
                                ];

                                //$this->stockReportSendMail($erow->email,$data);
                            }
                        }
                    }
                }
            }
        }

        if(isset($ProductInfo) && !empty($ProductInfo)) {
            //echo "<pre>";print_r($ProductInfo);echo "</pre>"; exit();
            foreach($ProductInfo as $k=>$rowDataList) {
    
                $getEmail  = Employee::where('employee_id',$k)->first();
                $sendEmail = $getEmail['email'];// ? $getEmail['email']:'sayed.giantssoft@gmail.com';
                Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList,'getEmail' => $getEmail], function($message) use ($rowDataList,$getEmail) {
                    //$message->to('sayed.giantssoft@gmail.com');
                    $message->to($getEmail['email']);
                    $message->subject('Current Stock Qty Status');
                    $message->from('demoadmin@manush.co.uk','Retail Gear');
                });
            }
        }
    }
}
