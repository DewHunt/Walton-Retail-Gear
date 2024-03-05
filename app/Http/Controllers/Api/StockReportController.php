<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AuthorityMessage;
use App\Models\DelarDistribution;
use App\Models\DealerInformation;
use App\Models\Employee;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use App\Models\Products;
use App\Models\PushNotification;
use Carbon\Carbon;
use DB;
use Response;
use Validator;
use Pagination;
use DataTables;
use Storage;
use Mail;
date_default_timezone_set('Asia/Dhaka');
class StockReportController extends Controller
{
 
    public function index(Request $request)
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
                        $getStockInfo = checkModelStock($row['Model']);

                        if(isset($getStockInfo) && !empty($getStockInfo)) {
                            if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null ) {
                                if($row['StockQuantity'] >= $getStockInfo->yeallow_qty && $row['StockQuantity'] < $getStockInfo->default_qty) {
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
                                elseif($row['StockQuantity'] < $getStockInfo->yeallow_qty && $row['StockQuantity'] >= $getStockInfo->red_qty) {
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
            foreach($ProductInfo as $k=>$rowDataList) {

                $getEmail  = Employee::where('employee_id',$k)->first();
                $sendEmail = $getEmail['email'];

                Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList,'getEmail' => $getEmail], function($message) use ($rowDataList,$getEmail) {
                    //$message->to('sayed.giantssoft@gmail.com');
                    $message->to($getEmail['email']);
                    $message->subject('Stock Update');
                    $message->from('demoadmin@manush.co.uk','Retail Gear');
                });
            }
        }
    }
}
