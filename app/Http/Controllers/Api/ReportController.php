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
class ReportController extends Controller
{
    
    public function report_dashboard()
    {
    	Log::info('Load Report Dashboard');
        return view('admin.report.dashboard');
    }

    public function bpSearch(Request $request)
    {
    	$search = $request->search;
        $bpList = "";
		if($search == '') 
		{
			$bpList = BrandPromoter::orderby('bp_name','asc')
			->select('id','bp_name','bp_phone')
			->get();
		}
		else 
		{
			$bpList = BrandPromoter::orderby('bp_name','asc')
			->select('id','bp_name','bp_phone')
			->where('bp_name', 'like', '%' .$search . '%')
			->orWhere('bp_phone','like', '%' .$search . '%')
			->orWhere('bp_id', $search)
			->get();
		}

        $response = array();
        foreach($bpList as $row) {
            $label = $row->bp_name." (Mo:".$row->bp_phone.")";
            $response[] = array("value"=>$row->id,"label"=>$label);
        }
        return response()->json($response);
    }

    public function retailerSearch(Request $request)
    {
    	$search = $request->search;
        $retailList = "";
		if($search == '') 
		{
			$retailList = Retailer::orderby('retailer_name','asc')
			->select('id','retailer_name','phone_number')
			->get();
		}
		else 
		{
			$retailList = Retailer::orderby('retailer_name','asc')
			->select('id','retailer_name','phone_number')
			->where('retailer_name', 'like', '%' .$search . '%')
			->orWhere('phone_number','like', '%' .$search . '%')
			->orWhere('retailer_id', $search)
			->get();
		}

        $response = array();
        foreach($retailList as $row) {
            $label = $row->retailer_name." (Mo:".$row->phone_number.")";
            $response[] = array("value"=>$row->id,"label"=>$label);
        }
        return response()->json($response);
    }

    public function salesReportForm(Request $request)
    {
    	$bpList 		= BrandPromoter::get(['bp_id','bp_name']);
    	$retailerList 	= Retailer::get(['retailer_id','retailer_name']);

        $month_Sdate    =  date('Y-m-01 00:00:00');
        $month_Edate    =  date('Y-m-t 23:59:59');

        
        $saleList = DB::table('sales')
        ->where('status','=',0)
        ->where('sale_date','>=',$month_Sdate)
        ->where('sale_date','<=',$month_Edate)
        //->whereBetween('sale_date',[$month_Sdate,$month_Edate])
        ->orderBy('id','DESC')
        ->paginate(100);

        foreach($saleList as $sale)
        {
            $saleProductList = DB::table('sale_products')
            ->select('*')
            ->where('sales_id',$sale->id)
            ->get();

            $dealerInfo = DB::table('dealer_informations')
            ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
            ->where('dealer_code',$sale->dealer_code)
            ->orWhere('alternate_code',$sale->dealer_code)
            ->first();

            foreach ($saleProductList as $saleProduct) {
                $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
              //  $sale->product_list = $saleProduct;
            }

            $sale->product_list=$saleProductList;

            $retailerInfo = DB::table('retailers')
            ->select('retailer_name as name','retailder_address as address','phone_number as phone')
            ->where('retailer_id',$sale->retailer_id)
            ->first();

            $brandPromoterInfo = DB::table('brand_promoters')
            ->select('bp_name as name','bp_phone as phone')
            ->where('bp_id',$sale->bp_id)
            ->first();

            $sale->retailer_info = $retailerInfo;
            $sale->bp_info = $brandPromoterInfo;
        }

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $saleList = DB::table('sales')
            ->where('status','=',0)
            ->where('sale_date','>=',$month_Sdate)
            ->where('sale_date','<=',$month_Edate)
            ->orWhere('customer_name','like', '%'.$query.'%')
            ->orWhere('customer_phone','like', '%'.$query.'%')
            ->orWhere('order_type','like', '%'.$query.'%')
            ->orWhere('status',$query)
            ->orderBy('id','DESC')
            ->paginate(100);

            return view('admin.report.sales_report_result_data', compact('saleList'))->render();
        }

        if(isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Product Sale List');
        } else {
            Log::warning('Product Sale List Not Found');
        }
    	return view('admin.report.sales_report',compact('saleList'));
    }

    public function dateRangesalesReport(Request $request)
    {
        $bpId      = 0;
        $retailId  = 0;
        if($request->input('bp_id')) {
            $bpId       = $request->input('bp_id');
        } else {
            $retailId   = $request->input('retailer_id');
        }

        $start_date =  $request->input('start_date');
        $end_date   =  $request->input('end_date');

        $currentSdate   = date('Y-m-d');
        $reqSdate       = "";
        $reqEdate       = "";
        $todate         = "";


        if ($start_date !== null && $end_date !== null) {
        	//$reqSdate  	= Carbon::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
        	//$todate   = Carbon::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');
        	$reqSdate   = $start_date;
            $todate     = $end_date;
    	}

        if(strtotime($todate) > strtotime($currentSdate)){
            $reqEdate = date('Y-m-d');
        } else {
            $reqEdate = $todate;
        }

        $status         = true;
        $effectiveDate = date('Y-m-d');
    	$effectiveDate = date('Y-m-d', strtotime("-3 months", strtotime($effectiveDate)));
        if($reqSdate == "" && $reqEdate == "") {
        	$saleList = DB::table('sales')
			->where('bp_id',$bpId)
			->where('retailer_id',$retailId)
			->whereBetween('sale_date',[$effectiveDate,$currentSdate])
			->paginate(100);

			foreach($saleList as $sale)
            {
                $saleProductList = DB::table('sale_products')
                ->select('*')
                ->where('bp_id',$bpId)
                ->where('retailer_id',$retailId)
                ->where('sales_id',$sale->id)
                ->get();

                $dealerInfo = DB::table('dealer_informations')
                ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                ->where('dealer_code',$sale->dealer_code)
                ->orWhere('alternate_code',$sale->dealer_code)
                ->first();

                foreach ($saleProductList as $saleProduct) {
                    $saleProduct->dealer_name = $dealerInfo->name;
                    $saleProduct->dealer_phone = $dealerInfo->phone;
                    $saleProduct->dealer_code = $dealerInfo->code;
                    $saleProduct->alternet_code = $dealerInfo->alternate_code;
                  //  $sale->product_list = $saleProduct;
                }

                $sale->product_list=$saleProductList;

                $retailerInfo = "";
                if($sale->retailer_id) {
                    $retailerInfo = DB::table('retailers')
                    ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                    ->where('retailer_id',$sale->retailer_id)
                    ->first();
                }

                $brandPromoterInfo = "";
                if(isset($bpId) && $bpId > 0)
                {
                    $brandPromoterInfo = DB::table('brand_promoters')
                    ->select('bp_name as name','bp_phone as phone')
                    ->where('bp_id',$bpId)
                    ->first();
                }
                
            }

            if(count($saleList) > 0) {
                return view('admin.report.sales_report',compact('saleList','retailerInfo','brandPromoterInfo'))->with('success','Sales Data Found');
            } else {
                Log::warning(' Date Range Sales List Data Not Found');
                return redirect()->action([ReportController::class, 'salesReportForm'])->with('error','Data Not Found.Please Try Again');
	        }
        }
        else 
        {
        	if(strtotime($reqEdate) <= strtotime($currentSdate) && strtotime($reqSdate) <= strtotime($currentSdate)) {

	            if (strtotime($reqSdate) <= strtotime($reqEdate)) {
	                $d                        =  strtotime("-3 Months");
	                $currentSdate             =  date('Y-m-d');
	                $afterThreeMonthLastDate  =  date("Y-m-d", $d);

	                $diff = strtotime($reqSdate) - strtotime($reqEdate);
	                // 1 day = 24 hours // 24 * 60 * 60 = 86400 seconds 
	                $totalDay  =  abs(round($diff / 86400))+1;

	                if($totalDay <= 90) {
	                    if ($totalDay > 30) {
	                        $reqEdate = date('Y-m-d', strtotime($reqEdate . ' -30 day'));
	                    }
	                    $saleList = DB::table('sales')
	                    ->orWhere(function($query) use($bpId,$retailId,$reqSdate,$reqEdate){

	                    	if ($reqSdate && $reqEdate) {
			                    if ($bpId > 0) {
			                        //$query->whereBetween('sale_date',[$reqSdate,$reqEdate]);
			                        $query->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$reqSdate,$reqEdate]);
			                        $query->where('id',$bpId);
			                    } 
			                    else if ($retailId > 0) {
			                        //$query->whereBetween('start_date',[$reqSdate,$reqEdate]);
			                        $query->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$reqSdate,$reqEdate]);
			                        $query->where('retailer_id',$retailId);
			                    }
			                    else {
			                        //$query->orWhereBetween('sale_date',[$reqSdate,$reqEdate]);
			                        $query->orWhereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$reqSdate,$reqEdate]);
			                    }                    
			                }

	                    })
	                    ->paginate(100);

	                    foreach($saleList as $sale)
	                    {
	                        $saleId = $sale->id;
	                        $saleProductList = DB::table('sale_products')
	                        ->select('*')
	                        ->orWhere(function($query) use($bpId,$retailId,$saleId){
    	                    	if ($bpId > 0) {
    		                        $query->where('id',$bpId);
    		                        $query->where('sales_id',$sale->id);
    		                    } 
    		                    else if ($retailId > 0) {
    		                        $query->where('retailer_id',$retailId);
    		                        $query->where('sales_id',$sale->id);
    		                    }
    		                    else {
    		                        $query->where('sales_id',$saleId);
    		                    } 
    	                    })
    	                    ->get();

	                        //$sale->product_list=$saleProductList;

                            $dealerInfo = DB::table('dealer_informations')
                            ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                            ->where('dealer_code',$sale->dealer_code)
                            ->orWhere('alternate_code',$sale->dealer_code)
                            ->first();

                            foreach ($saleProductList as $saleProduct) {
                                $saleProduct->dealer_name = $dealerInfo->name;
                                $saleProduct->dealer_phone = $dealerInfo->phone;
                                $saleProduct->dealer_code = $dealerInfo->code;
                                $saleProduct->alternet_code = $dealerInfo->alternate_code;
                              //  $sale->product_list = $saleProduct;
                            }
                                $sale->product_list=$saleProductList;

                            $retailerInfo = "";
                            if($sale->retailer_id) {
                                $retailerInfo = DB::table('retailers')
                                ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                                ->where('retailer_id',$sale->retailer_id)
                                ->first();
                            }

                            $brandPromoterInfo = "";
                            if(isset($bpId) && $bpId > 0)
                            {
                                $brandPromoterInfo = DB::table('brand_promoters')
                                ->select('bp_name as name','bp_phone as phone')
                                ->where('bp_id',$bpId)
                                ->first();
                            }

	                    }

	                    if(count($saleList) > 0) {
	                        return view('admin.report.sales_report',compact('saleList','brandPromoterInfo','retailerInfo'))->with('success','Sales Data Found');
	                    }
	                    else {
                            Log::warning('Date Range Sales List Data Not Found');
	                        return redirect()->action([ReportController::class, 'salesReportForm'])->with('error','Data Not Found.Please Try Again');
	                    }
	                } else {
	                    $status = false;
	                }

	            } else {
	                $status = false;
	            }
	        } else {
	            $status = false;
	        }

        }

        if($status === false) {
            Log::warning('Sales List Data Not Found');
        	return redirect()->action([ReportController::class, 'salesReportForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function SaleOrderDetails($saleId)
    {
    	$salesInfo = DB::table('view_sales_reports')
		->where('id',$saleId)
		->first();

        $saleProductList = DB::table('view_sales_reports')
        ->select('*')
        ->where('id',$saleId)
        ->get();
        
    	return view('admin.report.sales_item_report',compact('salesInfo','saleProductList'))->with('success','Sales Data Found');
    }

    public function incentiveReportFrom_1(Request $request)
    {
        $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
        ->select('id','bp_id','category','retailer_id','bp_name','retailer_name',\DB::raw('SUM(incentive_amount) AS total_incentive'),\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
        ->where('sale_id','>',0)
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
            ->select('id','bp_id','category','retailer_id','bp_name','retailer_name',\DB::raw('SUM(incentive_amount) AS total_incentive'),\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
            ->where('sale_id','>',0)
            ->orWhere('category','like', '%'.$query.'%')
            ->orWhere('bp_name','like', '%'.$query.'%')
            ->orWhere('retailer_name','like', '%'.$query.'%')
            ->orWhere('incentive_sale_qty','=',$query)
            ->orWhere('incentive_amount','=',$query)
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);

            return view('admin.report.incentive_report_result_data', compact('salesIncentiveReportList'))->render();
        }

        if(isset($salesIncentiveReportList) && $salesIncentiveReportList->isNotEmpty()) {
            Log::info('Load Sales Incentive List');
            return view('admin.report.incentive_report',compact('salesIncentiveReportList'));
        } else {
            Log::warning('Sales Incentive List Not Found');
            return view('admin.report.incentive_report');
        }
    }
    
    public function incentiveReportFrom(Request $request)
    {
        $month_Sdate    =  date('Y-m-01 00:00:00');
        $month_Edate    =  date('Y-m-t 23:59:59');
        
        
        $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
        ->select('id','bp_id','category','retailer_id','bp_name','retailer_name',\DB::raw('SUM(incentive_amount) AS total_incentive'),\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
        ->where('incentive_date','>=',$month_Sdate)
        ->where('incentive_date','<=',$month_Edate)
        ->where('sale_id','>',0)
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
            ->select('id','bp_id','category','retailer_id','bp_name','retailer_name',\DB::raw('SUM(incentive_amount) AS total_incentive'),\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
            ->where('sale_id','>',0)
            ->where('incentive_date','>=',$month_Sdate)
            ->where('incentive_date','<=',$month_Edate)
            ->orWhere('bp_name','like', '%'.$query.'%')
            ->orWhere('category','like', '%'.$query.'%')
            ->orWhere('retailer_name','like', '%'.$query.'%')
            ->orWhere('incentive_sale_qty','=',$query)
            ->orWhere('incentive_amount','=',$query)
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);

            return view('admin.report.incentive_report_result_data', compact('salesIncentiveReportList'))->render();
        }

        if(isset($salesIncentiveReportList) && $salesIncentiveReportList->isNotEmpty()) {
            Log::info('Load Sales Incentive List');
            return view('admin.report.incentive_report',compact('salesIncentiveReportList'));
        } else {
            Log::warning('Sales Incentive List Not Found');
            return view('admin.report.incentive_report');
        }
    }

    public function incentiveReport(Request $request)
    {
    	$bpId      		= 0;
        $retailerId  	= 0;
        if($request->input('bp_id')) {
            $bpId           = $request->input('bp_id');
        } else {
            $retailerId     = $request->input('retailer_id');
        }
        
        $incentiveCat       = $request->input('incentive_category');

        $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
        ->select('id','bp_id','retailer_id','category','bp_name','retailer_name',\DB::raw('SUM(incentive_amount) AS total_incentive'),'zone',\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
        ->orWhere(function($query) use($incentiveCat,$bpId,$retailerId) {
            
            if(!empty($incentiveCat)) {
                $query->where('category',$incentiveCat);
            }

        	if($bpId > 0){
        		$query->where('bp_id',$bpId);
                $query->groupBy('bp_id');
        	}
        	elseif($retailerId > 0) {
        		$query->where('retailer_id',$retailerId);
                $query->groupBy('retailer_id');
        	}
            elseif($bpId <= 0 || $retailerId <= 0)
            {
                $query->where('sale_id','>',0);
                $query->groupBy(['bp_id,retailer_id']);
            }
        })
        ->paginate(100);

        if(isset($salesIncentiveReportList) && !empty($salesIncentiveReportList)) {
            return view('admin.report.incentive_report',compact('salesIncentiveReportList'))->with('success','Data Found');
        } else {
           Log::warning('Report Module Incentive Data Not Found');
           return redirect()->action([ReportController::class, 'incentiveReportFrom'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function SaleIncentiveDetails(Request $request)
    {
        $bpId           = 0;
        $retailerId     = 0;
        $report_title   = "";
        if($request->bp) {
            $bpId           = $request->bp;
            $report_title   = "BP";
        } 
        elseif($request->retailer) {
            $retailerId     = $request->retailer;
            $report_title   = "Retailer";
        }


        $salesIncentiveReportDetails = DB::table('view_sales_incentive_reports')
        ->orWhere(function($query) use($bpId,$retailerId){
            if($bpId > 0){
                $query->where('bp_id',$bpId);
            }
            elseif($retailerId > 0) {
                $query->where('retailer_id',$retailerId);
            }
        })
        ->get();

        if(isset($salesIncentiveReportDetails) && !empty($salesIncentiveReportDetails)) {
            return view('admin.report.incentive_report_details',compact('salesIncentiveReportDetails','report_title'))->with('success','Data Found');
        } else {
           Log::warning('Report Module Sales Incentive Details Data Not Found');
           return redirect()->action([ReportController::class, 'incentiveReportFrom'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function bpAttendanceForm(Request $request)
    {
    	$month_Sdate    =  date('Y-m-01 00:00:00');
        $month_Edate    =  date('Y-m-t 23:59:59');


        $attendanceList = DB::table('view_bp_attendance_report')
        ->where('date_time','>=',$month_Sdate)
        ->where('date_time','<=',$month_Edate)
        //->whereBetween('date_time',[$month_Sdate,$month_Edate])
        ->orderBy('id','DESC')
        ->orderBy('date_time','DESC')
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $attendanceList = DB::table('view_bp_attendance_report')
            ->where('date_time','>=',$month_Sdate)
            ->where('date_time','<=',$month_Edate)
            ->orWhere('bp_name','like', '%'.$query.'%')
            ->orWhere('in_status','like', '%'.$query.'%')
            ->orWhere('out_status','like', '%'.$query.'%')
            ->orWhere('location','like', '%'.$query.'%')
            ->orWhere('date_time','like', '%'.$query.'%')
            ->orWhere('in_time','like', '%'.$query.'%')
            ->orWhere('out_time','like', '%'.$query.'%')
            ->orderBy($sort_by, $sort_type)
            ->orderBy('id','DESC')
            ->orderBy('date_time','DESC')
            ->paginate(100);
            
            return view('admin.report.bp_attendance_report_result_data', compact('attendanceList'))->render();
        }

        if(isset($attendanceList) && $attendanceList->isNotEmpty()) {
            Log::info('Load BP Attendance List');
            return view('admin.report.bp_attendance_report',compact('attendanceList'));
        } else {
            Log::warning('BP Attendance List Not Found');
            return view('admin.report.bp_attendance_report');
        }
    }

    public function bpAttendanceReport(Request $request)
    {
        $bpId       = 0;
        if($request->input('bp_id')) {
            $bpId   = $request->input('bp_id');
        }

        $start_date =  $request->input('start_date');
        $end_date   =  $request->input('end_date');

        $reqSdate = "";
        $reqEdate = "";
        if ($start_date !== null && $end_date !== null) {
        	//$reqSdate  	= Carbon::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
        	//$reqEdate   = Carbon::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');

            $reqSdate   = Carbon::createFromFormat('Y-m-d', $start_date)->format('Y-m-d');
            $reqEdate   = Carbon::createFromFormat('Y-m-d', $end_date)->format('Y-m-d');
    	}

        $attendanceList = "";

        if($bpId > 0) {
            $attendanceList = DB::table('view_bp_attendance_report')
            ->where('id',$bpId)
            ->orwhereBetween('date_time',[$reqSdate,$reqEdate])
            ->paginate(100);
        } else {
            $attendanceList = DB::table('view_bp_attendance_report')
            ->orwhereBetween('date_time',[$reqSdate,$reqEdate])
            ->paginate(100);
        }
        

        if(isset($attendanceList) && !empty($attendanceList)) {
            Log::info('Get BP Attendance By Id');
            return view('admin.report.bp_attendance_report',compact('attendanceList'))->with('success','Data Found');
        } else {
           Log::warning('BP Attendance Not Found By Id');
           return redirect()->action([ReportController::class, 'bpAttendanceForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function bpAttendanceDetails($bpId,$attendanceDate)
    {
    	$attendanceDate = date('Y-m-d', strtotime($attendanceDate));
    	$attendanceList = DB::table('bp_attendances')
        ->where('bp_attendances.bp_id',$bpId)
        ->where('bp_attendances.date','like','%'.$attendanceDate.'%')
        ->leftJoin('brand_promoters', 'bp_attendances.bp_id', '=', 'brand_promoters.bp_id')
        ->get();

        if(isset($attendanceList) && !empty($attendanceList)) {
            Log::info('Get BP Attendance Details By Id');
            return view('admin.report.bp_attendance_report_details',compact('attendanceList'))->with('success','Data Found');
        } else {
          Log::warning('BP Attendance Details Not Found');
           return redirect()->action([ReportController::class, 'bpAttendanceForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function bpLeaveReportForm(Request $request)
    {
        $month_Sdate       =  date('Y-m-01');
        $month_Edate       =  date('Y-m-t');

        $leaveList = DB::table('view_bp_leave_report')
        ->whereBetween('start_date',[$month_Sdate,$month_Edate])
        ->paginate(100);
        
        //echo "<pre>";print_r($leaveList); echo "</pre>";exit();
        

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $leaveList = DB::table('view_bp_leave_report')
            ->whereBetween('start_date',[$month_Sdate,$month_Edate])
            ->orWhere('bp_name','like', '%'.$query.'%')
            ->orWhere('leave_type','like', '%'.$query.'%')
            ->orWhere('apply_date','like', '%'.$query.'%')
            ->orWhere('start_date','like', '%'.$query.'%')
            ->orWhere('reason','like', '%'.$query.'%')
            ->orWhere('total_day','=',$query)
            ->orWhere('start_time','=',$query)
            ->orWhere('status',$query)
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);

            return view('admin.report.bp_leave_report_result_data', compact('leaveList'))->render();
        }

        if(isset($leaveList) && $leaveList->isNotEmpty()) {
            Log::info('Load BP Leave List');
        } else {
            Log::warning('BP Leave List Not Found');
        }
        return view('admin.report.bp_leave_report',compact('leaveList'));
    }

    public function bpLeaveReport(Request $request)
    {
        $bpId       = 0;
        if($request->input('bp_id')) {
            $bpId   = $request->input('bp_id');
        }

        $start_date =  $request->input('start_date');
        $end_date   =  $request->input('end_date');
        
        $reqSdate    =  date('Y-m-01');
        $reqSdate    =  date('Y-m-t');

        //$reqSdate = date('Y-m-d');
        //$reqSdate = date('Y-m-d');
        if ($start_date !== null && $end_date !== null) {
        	//$reqSdate  	= Carbon::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
        	//$reqEdate   = Carbon::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');

            $reqSdate   = Carbon::createFromFormat('Y-m-d', $start_date)->format('Y-m-d');
            $reqEdate   = Carbon::createFromFormat('Y-m-d', $end_date)->format('Y-m-d');
    	}

        /*
        $leaveList = DB::table('view_bp_leave_report')
        ->orWhere(function($query) use($reqSdate, $reqEdate, $bpId){
            if ($reqSdate && $reqEdate) {
                if ($bpId > 0) {
                    $query->whereBetween('start_date',[$reqSdate,$reqEdate]);
                    $query->where('bp_id',$bpId);
                } else {
                    $query->orWhereBetween('start_date',[$reqSdate,$reqEdate]);
                }                    
            }
        })
        ->paginate(100);
        */
        
        //echo "Check-".$reqSdate.'-'.$reqEdate; exit();
        $leaveList = DB::table('view_bp_leave_report')
        ->when($bpId, function ($query, $bpId) {
            return $query->where('bp_id','=',$bpId);
        })
        ->when($reqSdate, function ($query, $reqSdate) {
            return $query->where('start_date','>=', $reqSdate);
        })
        ->when($reqEdate, function ($query, $reqEdate) {
            return $query->where('start_date','<=', $reqEdate);
        })
        ->paginate(100);

        if(isset($leaveList) && !empty($leaveList)) {
            Log::info('BP Leave List Not Found');
            return view('admin.report.bp_leave_report',compact('leaveList'))->with('success','Data Found');
        } else {
           Log::warning('BP Leave List Not Found');
           return redirect()->action([ReportController::class, 'bpLeaveReportForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function imeSoldReport(Request $request)
    {
        $DelarDistributionModel = new DelarDistribution;
        $DelarDistributionModel->setConnection('mysql2');

        $soldImeList = $DelarDistributionModel::where('status',0)->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $soldImeList = $DelarDistributionModel::where('status',0)
            ->where('barcode', 'like', '%'.$query.'%')
            ->orWhere('barcode2', 'like', '%'.$query.'%')
            ->where('status',0)
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);
            return view('admin.report.sold_ime_result_data', compact('soldImeList'))->render();
        }

        if(isset($soldImeList) && $soldImeList->isNotEmpty()) {
            Log::info('Load Sold IMEI List');
        } else {
            Log::warning('Sold IMEI List Not Found');
        }
        return view('admin.report.sold_ime_list',compact('soldImeList'));
    }

    public function imeProductDetails($id)
    {
        if(isset($id) && $id > 0) {
            $productDetails = DB::table('view_product_master')
            ->where('product_master_id',$id)
            ->first();

            if($productDetails) {
                Log::info('Get IMEI Product Details By Id');
                return response()->json($productDetails);
            } else {
                 Log::warning('IMEI Product Not Found By Id');
                 return response()->json('error');
            }
        }
    }
    
    public function OrderDetailsView($saleId)
    {
        $salesInfo = DB::table('view_sales_reports')
        ->where('id',$saleId)
        ->first();

        $saleProductList = DB::table('view_sales_reports')
        ->select('*')
        ->where('id',$saleId)
        ->get();

        $viewSalesInfo = "<tbody><tr><th scope='row'>Customer Name:</th><td>".$salesInfo->customer_name."</td><th scope='row'>Customer Phone:</th><td>".$salesInfo->customer_phone."</td></tr><tr><th scope='row'>Sale Date:</th><td>".$salesInfo->sale_date."</td><th scope='row'>BP Name:</th><td>".$salesInfo->bp_name."</td></tr><tr><th scope='row'>BP Phone:</th><td>".$salesInfo->bp_phone."</td><th scope='row'>Retailer Name:</th><td>".$salesInfo->retailer_name."</td></tr><tr><th scope='row'>Retailer Phone:</th><td>".$salesInfo->retailer_phone_number."</td><th scope='row'>Retailer Address:</th><td>".$salesInfo->retailder_address."</td></tr><tr><th scope='row'>Comments:</th><td colspan='3'>".$salesInfo->note."</td></tr></tbody>";

        $viewItemList = [];
        
        //$baseUrl = URL::to('');
        //$pathUrl = $baseUrl.'/public/upload/client/';
        
        $i=1;
        foreach($saleProductList as $row) {
            
            $baseUrl = URL::to('');
            $imagePath = "";
            
            if(!empty($row->photo) && $row->photo !=null) {
                $imagePath = $row->photo;
            }
            else {
                $imagePath = $baseUrl.'/public/upload/no-image.png';
            }
            $viewItemList[] = "<tr><td>".$i.".</td><td><img src=".$imagePath." width='50' height='50'/></td><td>".$row->ime_number."</td><td>".$row->product_code."</td><td>".$row->product_type."</td><td>".$row->product_model."</td><td>".$row->product_color."</td><td>".number_format($row->msrp_price,2)."</td><td>".number_format($row->sale_price,2)."</td><td>".$row->sale_qty."</td></tr>";
        
        $i++;
        }

        if($viewSalesInfo) {
            Log::info('Load Sales Info');
            return response()->json(['salesInfo'=>$viewSalesInfo,'itemList'=>$viewItemList,'saleId'=>$saleId]);
        } else {
            Log::warning('Sales Info Not Found');
             return response()->json('error');
        }
    }
    
    public function MessageDetails($replyId,$messageId)
    {
        $imagePath = "no-image.png";
        $baseUrl = URL::to('');
        $pathUrl = $baseUrl.'/public/upload/no-image.png';

        $authorMessage = DB::table('authority_messages')
        ->where('id','=',$messageId)
        ->where('status',0)
        ->first();

        $AuthuserName   = "";
        $replyDateTime  = "";
        $AuthMessage    = "";
        if(isset($authorMessage->reply_user_name)){
            $AuthuserName   = $authorMessage->reply_user_name;
            $replyDateTime  = $authorMessage->date_time;
            $AuthMessage    = $authorMessage->message;
        }

        $authorMessageView = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$AuthMessage.'</p></div><p class="small text-muted">'.$AuthuserName.'|'.$replyDateTime.'</p></div></div>';

        $messageList = DB::table('authority_messages')
        ->orWhere(function($query) use($replyId, $messageId){
            if($replyId > 0){
                $query->where('id','=',$replyId);
                $query->orWhere('reply_for','=',$replyId);
            } else {
                $query->where('id','=',$messageId);
            }
        })->get();
        

        $replyMessageArray= [];
        foreach($messageList as $key=>$message)
        {
            if($message->status == 0) {
                $replyMessageArray[$key]["author"]      = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"]    = $message->date_time;
                $replyMessageArray[$key]["message"]     = $message->message;
            }

            if($message->status == 1) {
                $replyMessageArray[$key]["reply"]       = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"]    = $message->date_time;
                $replyMessageArray[$key]["message"]     = $message->message;
            }
        }

        $viewMessage   = [];
        foreach($replyMessageArray as $key=>$messageInfo)
        {
            if(isset($messageInfo['author'])) {
                $viewMessage[] = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["author"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            } else {

                $viewMessage[] = '<div class="media w-50 ml-auto"><div class="media-body"><div class="bg-primary rounded"><p class="text-small mb-0 text-white" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["reply"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
        }


        return response()->json(['sendMessage'=>$authorMessageView,'replyMessage'=>$viewMessage,'messageId'=>$messageId,'replyId'=>$replyId]);
    }

    public function reply_message_old(Request $request)
    {
        $imagePath  = "no-image.png";
        $baseUrl    = URL::to('');
        $pathUrl    = $baseUrl.'/public/upload/no-image.png';


        $messageId  = $request->input('message_id');
        $replyId    = $request->input('reply_id');
        $user       = Auth::user();
        $userId     = $user->id;
        //$userId     = auth('api')->user()->id;

        $userExists = DB::table('users')
        ->where('id',$userId)
        ->first();

        $messageInfo = DB::table('authority_messages')
        ->where('id',$messageId)
        ->first();

        $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();

        $messageStatus  = 1;
        
        if(isset($CheckStatus) && $CheckStatus['who_reply'] == $userId && $CheckStatus['id'] == $messageId) {
            $messageStatus = 0;
        }
        
        $bnm     = 0; //bnm = Brand New Message
        if(isset($CheckStatus) && $CheckStatus['bnm'] == 2) {
            $bnm = 1; 
        }

        $reply_status = 0;
        if($CheckStatus) {
            $AddMessage = AuthorityMessage::create([
                "message"=>$request->input('reply_message'),
                "date_time"=>date('Y-m-d H:i:s'),
                "status"=>$messageStatus,
                //'reply_for'=>$messageId ? $messageId:0,
                'reply_for'=>$replyId ? $replyId:$messageId,
                'who_reply'=> $userId ? $userId:0,
                "reply_user_name"=>$userExists->name
            ]);
            
            $updateBnmStatus = AuthorityMessage::where('id',$messageId)
            ->update([
                "bnm"=>$bnm ? $bnm:0
            ]);
            
            if($AddMessage) {
                $reply_status = 1;
            }
        } 

        $authorMessage = DB::table('authority_messages')
        ->where('id', $messageId)
        ->where('status',0)
        ->first();

        $authorMessageView = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$authorMessage->message.'</p></div><p class="small text-muted">'.$authorMessage->reply_user_name.'|'.$authorMessage->date_time.'</p></div></div>';


        /*$messageList = DB::table('authority_messages')
        ->where('reply_for', $messageId)
        ->orderBy('id','asc')
        ->get();*/
        
        
        $messageList = DB::table('authority_messages')
        ->orWhere(function($query) use($replyId, $messageId){
            if($replyId > 0){
                $query->where('id','=',$replyId);
                $query->orWhere('reply_for','=',$replyId);
            } else {
                $query->where('id','=',$messageId);
            }
        })->get();

        $replyMessageArray= [];
        foreach($messageList as $key=>$message)
        {
            if($message->status == 0){
                $replyMessageArray[$key]["author"]      = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"]    = $message->date_time;
                $replyMessageArray[$key]["message"]     = $message->message;
            }

            if($message->status == 1){
                $replyMessageArray[$key]["reply"]       = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"]    = $message->date_time;
                $replyMessageArray[$key]["message"]     = $message->message;
            }

        }

        $viewMessage   = [];
        foreach($replyMessageArray as $key=>$messageInfo)
        {
            if(isset($messageInfo['author'])) {

                 $viewMessage[] = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["author"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
            else
            {

                $viewMessage[] = '<div class="media w-50 ml-auto"><div class="media-body"><div class="bg-primary rounded"><p class="text-small mb-0 text-white" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["reply"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
        }

        if( $reply_status == 1) {
            return response()->json(['status'=>'success','sendMessage'=>$authorMessageView,'replyMessage'=>$viewMessage,'messageId'=>$messageId]);
        } else {
             return response()->json(['status'=>'error']);
        }
    }
    
    public function reply_message(Request $request)
    {
        $imagePath  = "no-image.png";
        $baseUrl    = URL::to('');
        $pathUrl    = $baseUrl.'/public/upload/no-image.png';


        $messageId  = $request->input('message_id');
        $replyId    = $request->input('reply_id');
        $user       = Auth::user();
        $userId     = $user->id;
        //$userId     = auth('api')->user()->id;

        $userExists = DB::table('users')
        ->where('id',$userId)
        ->first();

        $messageInfo = DB::table('authority_messages')
        ->where('id',$messageId)
        ->first();

        $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();

        $messageStatus  = 1;
        
        if(isset($CheckStatus) && $CheckStatus['who_reply'] == $userId && $CheckStatus['id'] == $messageId) {
            $messageStatus = 0;
        }
        
        $bnm     = 0; //bnm = Brand New Message
        if(isset($CheckStatus) && $CheckStatus['bnm'] == 2) {
            $bnm = 1; 
        }
        $lastInsertId = "";
        $reply_status = 0;
        if($CheckStatus) {
            $AddMessage = AuthorityMessage::create([
                "message"=>$request->input('reply_message'),
                "date_time"=>date('Y-m-d H:i:s'),
                "status"=>$messageStatus,
                //'reply_for'=>$messageId ? $messageId:0,
                'reply_for'=>$replyId ? $replyId:$messageId,
                'who_reply'=> $userId ? $userId:0,
                "reply_user_name"=>$userExists->name
            ]);

            $lastInsertId = DB::getPdo()->lastInsertId(); 
            
            $updateBnmStatus = AuthorityMessage::where('id',$messageId)
            ->update([
                "bnm"=>$bnm ? $bnm:0
            ]);
            
            if($AddMessage) {
                $reply_status = 1;
            }
        } 

        $authorMessage = DB::table('authority_messages')
        //->where('id', $messageId)
        ->where('id', $lastInsertId)
        // ->where('status',0)
        ->first();
        
        // echo "<pre>"; print_r($authorMessage->message);exit();
        
        $authorMessageView = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$authorMessage->message.'</p></div><p class="small text-muted">'.$authorMessage->reply_user_name.'|'.$authorMessage->date_time.'</p></div></div>';
        
        
        $messageList = DB::table('authority_messages')
        ->orWhere(function($query) use($replyId, $messageId){
            if($replyId > 0){
                $query->where('id','=',$replyId);
                $query->orWhere('reply_for','=',$replyId);
            } else {
                $query->where('id','=',$messageId);
            }
        })->get();

        $replyMessageArray= [];
        foreach($messageList as $key=>$message)
        {
            if($message->status == 0){
                $replyMessageArray[$key]["author"]      = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"]    = $message->date_time;
                $replyMessageArray[$key]["message"]     = $message->message;
            }

            if($message->status == 1){
                $replyMessageArray[$key]["reply"]       = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"]    = $message->date_time;
                $replyMessageArray[$key]["message"]     = $message->message;
            }

        }

        $viewMessage   = [];
        foreach($replyMessageArray as $key=>$messageInfo)
        {
            if(isset($messageInfo['author'])) {

                 $viewMessage[] = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["author"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
            else
            {

                $viewMessage[] = '<div class="media w-50 ml-auto"><div class="media-body"><div class="bg-primary rounded"><p class="text-small mb-0 text-white" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["reply"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
        }

        if( $reply_status == 1) {
            return response()->json(['status'=>'success','sendMessage'=>$authorMessageView,'replyMessage'=>$viewMessage,'messageId'=>$messageId]);
        } else {
             return response()->json(['status'=>'error']);
        }
    }

    public function editLeave($id)
    {
        if(isset($id) && $id > 0) {

            $month_Sdate       =  date('Y-m-01');
            $month_Edate       =  date('Y-m-t');
            $leaveInfo         =  DB::table('bp_leaves')->where('id',$id)->first();

            $leaveList = DB::table('view_bp_leave_report')
            ->where('bp_id','=',$leaveInfo->bp_id)
            ->where('status','=','Approved')
            ->whereBetween('start_date',[$month_Sdate,$month_Edate])
            ->get();

            $currentMonthBpLeaveList = [];

            if($leaveList->isNotEmpty())
            {
                foreach($leaveList as $row) {
                    $currentMonthBpLeaveList[] = '<tr><td>'.$row->leave_type.'</td><td>'.date('d-m-Y', strtotime($row->start_date)).'</td><td>'.$row->start_time.'</td><td>'.$row->total_day.'</td><td>'.$row->reason.'</td></tr>';
                }
            }
            else
            {
                $currentMonthBpLeaveList[] = '<tr><td colspan="5" class="text-center text-danger">Leave Status Not Found </td></tr>';
            }


            
            $leaveTypes         = DB::table('leave_types')->get();
            $leaveCategories    = DB::table('leave_categories')->get();

            $leaveType = [];
            foreach($leaveTypes as $ltype)
            {
                $leaveType[] = '<option value="'.$ltype->id.'">'.$ltype->name.'</option>';
            }

            $leaveReason = [];
            foreach($leaveCategories as $cat)
            {
                $leaveReason[] = '<option value="'.$cat->id.'">'.$cat->name.'</option>';
            }
            Log::info('Edit Leave By Id');
            return response()->json(['leaveInfo'=>$leaveInfo,'leaveType'=>$leaveType,'leaveReason'=>$leaveReason,'leaveList'=>$currentMonthBpLeaveList]);
        } else {
            Log::warning('Edit Leave Failed By Id');
            return redirect()->back()->with('error');
        }
    }

    public function updateLeave(Request $request,$update_id)
    {
        $rules = [
            'total_day'=>'required',
            'status'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Leave Update Validation error');
            return Response::json(['errors' => $validator->errors()]);
        }

        $Update = DB::table('bp_leaves')
        ->where('id',$update_id)
        ->update([
            "total_day"=>$request->input('total_day'),
            "start_time"=>$request->input('start_time'),
            "status"=>$request->input('status'),
        ]);

        if($Update) {
            Log::info('Existing Leave Update');
            return response()->json('success');
        }
        Log::info('Existing Leave Update Failed');
        return response()->json('error');
    }

    public function attendanceDetailsView($id)
    {
        $attendanceInfo = DB::table('bp_attendances')->where('id',$id)->first();
        $attendanceDate = date('Y-m-d', strtotime($attendanceInfo->date));
        $attendanceList = DB::table('bp_attendances')
        ->where('bp_attendances.bp_id',$attendanceInfo->bp_id)
        ->where('bp_attendances.date','like','%'.$attendanceDate.'%')
        ->leftJoin('brand_promoters', 'bp_attendances.bp_id', '=', 'brand_promoters.bp_id')
        ->get();

        $imagePath = "no-image.png";
        $baseUrl = URL::to('');
        $pathUrl = $baseUrl.'/public/upload/bpattendance/';

        $attendanceArrayList = [];
        foreach($attendanceList as $row)
        {
            $remarkStatus = "";
            $remarksDate  = "";
            if($row->remarks == 1) {
                $remarkStatus = "<b>First In</b><br/>";
                $remarksDate  = $row->date;
            }
            elseif($row->remarks == 2) {
                $remarkStatus = "<b>First Out</b><br/>";
                $remarksDate  = $row->date;
            }
            elseif($row->remarks == 3) {
                $remarkStatus = "<b>Again In</b><br/>";
                $remarksDate  = $row->date;
            }
            elseif($row->remarks == 4) {
                $remarkStatus = "<b>Again Out</b><br/>";
                $remarksDate  = $row->date;
            }

            if(!empty($row->selfi_pic) && $row->selfi_pic !=null) {
                $imagePath = $row->selfi_pic;
            }

            $attendanceArrayList[] = "<tr><td><img src=".$pathUrl.$imagePath." width='50' height='50'/></td><td>".$row->bp_name."</td><td>".$row->in_status."</td><td>".$row->out_status."</td><td>".$row->location."</td><td>".$remarkStatus.$remarksDate."</td></tr>";

        }

        if($attendanceArrayList) {
            return response()->json(['attendanceList'=>$attendanceArrayList]);
        } else {
            Log::warning('Report Module Attendance Data List Not Found');
            return response()->json('error');
        }
    }

    public function incentiveDetailsView($bp_id=null,$retail_id=null)
    {
        $bpId           = 0;
        $retailerId     = 0;
        if($bp_id > 0) {
            $bpId    = $bp_id;
        } 
        elseif($retail_id > 0) {
            $retailerId     = $retail_id;
        }

        $salesIncentiveReportDetails = DB::table('view_sales_incentive_reports')
        ->orWhere(function($query) use($bpId,$retailerId){
            if($bpId > 0){
                $query->where('bp_id',$bpId);
            }
            elseif($retailerId > 0) {
                $query->where('retailer_id',$retailerId);
            }
        })
        ->get();

        $incentiveArrayList =[];
        foreach($salesIncentiveReportDetails as $row)
        {
            $Name = !empty($row->bp_name) ? $row->bp_name : $row->retailer_name;

            $incentiveArrayList[] = "<tr><td>".$row->id.".</td><td>".$row->ime_number."</td><td>".$Name."</td><td>".$row->product_model."</td><td>".$row->incentive_sale_qty."</td><td>".number_format($row->incentive_amount,2)."</td><td>".$row->start_date."</td></tr>";
        }

        if($incentiveArrayList) {
            return response()->json(['incentiveReportList'=>$incentiveArrayList]);
        } else {
            Log::warning('Report Module Incentive Details Data Not Found');
            return response()->json('error');
        }
    }

    public function productSalesReport(Request $request)
    {
        $month_Sdate       =  date('Y-m-01');
        $month_Edate       =  date('Y-m-t');

        $productModelList   = Products::distinct()->get(['product_master_id','product_model']);

        $productSalesReport = DB::table('view_sales_reports')
        ->select('id','customer_name','customer_phone','sale_date','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
        ->selectRaw('count(sale_qty) as saleQty')
        ->whereBetween('sale_date',[$month_Sdate,$month_Edate])
        ->groupBy('product_model')
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $DelarList = DB::table('view_sales_reports')
                ->where('id',$query)
                ->orWhere('sale_qty',$query)
                ->orWhere('product_model', 'like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.report.product_sales_result_data', compact('productSalesReport','productModelList'))->render();
        }

        return view('admin.report.product_sales_report',compact('productSalesReport','productModelList'));
    }
    
    public function modelSalesReport(Request $request)
    {
        $bpId      = 0;
        $retailId  = 0;
        $sellerName = "";
        if($request->input('bp_id')) {
            $bpId           = $request->input('bp_id');
            $sellerName     = BrandPromoter::where('status','=',1)
                            ->where('id','=',$bpId)
                            ->value('bp_name');
        } else {
            $retailId       = $request->input('retailer_id');
            $sellerName     = Retailer::where('status','=',1)
                            ->where('id','=',$retailId)
                            ->value('retailer_name');
        }

        $start_date =  $request->input('start_date');
        $end_date   =  $request->input('end_date');

        $reqSdate       = "";
        $reqEdate       = "";

        if ($start_date !== null) {
            //$reqSdate   = Carbon::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
            $reqSdate   = $start_date;
        } else {
            $reqSdate   = "Y-m-d";
        }

        if ($end_date !== null) {
            //$reqEdate   = Carbon::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');
            $reqEdate   = $end_date;
        } else {
            $reqEdate   = "Y-m-d";
        }

        $productModelList   = Products::distinct()->get(['product_master_id','product_model']);
        $modelNumber        = $request->input('model_name');


        $productSalesReport = DB::table('view_sales_reports')
        ->select('id','customer_name','customer_phone','sale_date','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone','bp_id','retailer_id')
        ->selectRaw('count(sale_qty) as saleQty')
        ->orWhere(function($query) use($reqSdate, $reqEdate, $bpId, $retailId) {
            if($bpId > 0) {
                $query->where('bp_id',$bpId);
            }
            else if($retailId > 0) {
                $query->where('retailer_id',$retailId);
            }
            else if($reqSdate && $reqEdate) {
                //$query->whereBetween('sale_date',[$reqSdate,$reqEdate]);
                $query->whereBetween(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$reqSdate,$reqEdate]);
            }
        })
        ->groupBy('product_model')
        ->paginate(100);

        //return view('admin.report.product_sales_report',compact('productSalesReport','productModelList'));

        return view('admin.report.seller_product_sales_report',compact('productSalesReport','productModelList','sellerName'));
    }
    
    public function productSalesReportDetails($modelNumber)
    {
        $salesInfoList = DB::table('view_sales_reports')
        ->select('id','sale_date','sale_qty','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
        ->where('product_model',$modelNumber)
        ->get();

        $viewProductInfo = [];
        $i = 1;
        foreach($salesInfoList as $salesInfo)
        {
            //$viewProductInfo[] = "<tr><th scope='row'>IMEI:</th><td>".$salesInfo->ime_number."</td></tr><tr><th scope='row'>Model:</th><td>".$salesInfo->product_model."</td></tr><tr><th scope='row'>Color:</th><td>".$salesInfo->product_color."</td></tr><tr><th scope='row'>Msrp:</th><td>".$salesInfo->msrp_price."</td></tr><tr><th scope='row'>Mrp:</th><td>".$salesInfo->mrp_price."</td></tr><tr><th scope='row'>Sale Price:</th><td>".$salesInfo->sale_price."</td></tr><tr><th scope='row'>Qty:</th><td>".$salesInfo->sale_qty."</td></tr><tr><th scope='row'>BP Name:</th><td>".$salesInfo->bp_name."</td></tr><tr><th scope='row'>BP Phone:</th><td>".$salesInfo->bp_phone."</td></tr><tr><th scope='row'>Retailer Name:</th><td>".$salesInfo->retailer_name."</td></tr><tr><th scope='row'>Retailer Phone:</th><td>".$salesInfo->retailer_phone_number."</td></tr><tr><th scope='row'>Sale Date:</th><td>".$salesInfo->sale_date."</td></tr>";


             $viewProductInfo[] = "<tr><td>".$i.".</td><td>".$salesInfo->ime_number."</td><td>".$salesInfo->product_model."</td><td>".$salesInfo->product_color."</td><td>".$salesInfo->msrp_price."</td><td>".$salesInfo->mrp_price."</td><td>".$salesInfo->msdp_price."</td><td>".$salesInfo->sale_price."</td><td>".$salesInfo->sale_qty."</td><td>".$salesInfo->bp_name.' - '.$salesInfo->bp_phone."</td><td>".$salesInfo->retailer_name.' - '.$salesInfo->retailer_phone_number."</td><td>".$salesInfo->sale_date."</td></tr>";

            $i++;
        }

        if($viewProductInfo) {
            return response()->json(['itemList'=>$viewProductInfo]);
        } else {
             return response()->json('error');
        }
    }

    public function sellerProductSalesReport($modelSellerId)
    {
        $getValue =explode('~',$modelSellerId);
        $modelNumber = $getValue[0];
        $seller      = $getValue[1];
        $sellerId    = $getValue[2];
        
        $salesInfoList = DB::table('view_sales_reports')
        ->select('id','sale_date','sale_qty','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
        ->where('product_model',$modelNumber)
        ->where($seller,$sellerId)
        ->get();

        $viewProductInfo = [];
        $i = 1;
        foreach($salesInfoList as $k=>$salesInfo)
        {
            $viewProductInfo[] = "<tr><td>".$i.".</td><td>".$salesInfo->ime_number."</td><td>".$salesInfo->product_model."</td><td>".$salesInfo->product_color."</td><td>".$salesInfo->msrp_price."</td><td>".$salesInfo->mrp_price."</td><td>".$salesInfo->msdp_price."</td><td>".$salesInfo->sale_price."</td><td>".$salesInfo->sale_qty."</td><td>".$salesInfo->bp_name.' - '.$salesInfo->bp_phone."</td><td>".$salesInfo->retailer_name.' - '.$salesInfo->retailer_phone_number."</td><td>".$salesInfo->sale_date."</td></tr>";
        
            $i++;
        }

        if($viewProductInfo) {
            return response()->json(['itemList'=>$viewProductInfo]);
        } else {
             return response()->json('error');
        }
    }
    
    public function getRetailerStock()
    {
        Log::info('Load Retailer Stock Dashboard');
        return view('admin.stock.retailer_stock_list');
    }

    public function searchRetailerStock(Request $request)
    {
        $clientType = $request->input('client_type');
        $searchId   = $request->input('search_id');
        $resultType = $request->input('result_type');

        $rules = [
            'client_type'=>'required',
            'search_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Retailer Stock Request Validation error');
            return redirect()->back()->with(['errors'=>$validator->errors()]);
        }

        if(isset($clientType) && $clientType == 'retailer') {
            $clientInfo = Retailer::where('phone_number','=',$searchId)->first();
        }
        else if(isset($clientType) && $clientType == 'dealer') {
            $clientInfo = DealerInformation::where('dealer_code','=',$searchId)
            ->orWhere('alternate_code','=',$searchId)
            ->first();
        }
        else if(isset($clientType) && $clientType == 'emp') {
            $clientInfo = Employee::where('employee_id','=',$searchId)->first();
        }

        if(isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {

            $getCurlResponse    = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
            $responseData       = json_decode($getCurlResponse['response_data'],true);

            Log::info('Load Retailer Stock');
            return view('admin.stock.retailer_stock_list',compact('responseData','clientInfo','resultType','clientType'));
        } else {
            Log::warning('Invalid Request Retailer Stock');
            return redirect()->back()->with('error');
        }
    }

    public function getStockResult(Request $request)
    {
        $clientType = $request->input('client_type');
        $searchId   = $request->input('search_id');
        $resultType = $request->input('result_type');

        $rules = [
            'client_type'=>'required',
            'search_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Retailer Stock Request Validation error');
            return redirect()->back()->with(['errors'=>$validator->errors()]);
        }

        if(isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {

            $getCurlResponse    = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
            $responseData       = json_decode($getCurlResponse['response_data'],true);

            Log::info('Load Retailer Stock');
            return view('admin.stock.retailer_stock_list',compact('responseData','clientType','resultType'));
        } else {
            Log::warning('Invalid Request Retailer Stock');
            return redirect()->back()->with('error');
        }
    }

    
    public function searchRetailerStock_old_bk_05_09_2021(Request $request)
    {
        $phone      = $request->input('dealer_phone'); //"01796452391";
        $dCode      = $request->input('dealer_code'); //"58133"; //Dealer Code

        if(empty($phone) && empty($dCode)) {
            $rules = [
                'dealer_phone'=>'required|min:11',
                'dealer_code'=>'required|min:5',
            ];

            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                Log::error('Retailer Stock Request Validation error');
                return redirect()->back()->with(['errors'=>$validator->errors()]);
            }
        }

        $DealerCode         = "";
        $DealerAlterNetCode = "";
        $getDealerPhone     = "";

        if(empty($dCode) || $dCode == "") {
            $getDealerCode = DB::table('dealer_informations')
            ->where('dealer_phone_number','=',$phone)
            ->first();

            if(!empty($getDealerCode)) {
                $DealerCode         = $getDealerCode->dealer_code;
                $DealerAlterNetCode = $getDealerCode->alternate_code;
            } else {
                return redirect()->back()->with('error');
            }

        } else {
            $getDealerPhone = DB::table('dealer_informations')
            ->where('dealer_code','=',$dCode)
            ->orWhere('alternate_code','=',$dCode)
            ->value('dealer_phone_number');
        }

        $finalDealerCode = "";

        if($DealerCode == "" && $DealerAlterNetCode == ""){
            $finalDealerCode  = $dCode;
        } else {
            $finalDealerCode  = $DealerCode ? $DealerCode:$DealerAlterNetCode;
        }
        
        $dPhone = $getDealerPhone ? $getDealerPhone:$phone;

        if(isset($dPhone) && !empty($dPhone) || isset($finalDealerCode)  && !empty($finalDealerCode)) {

            $dealerInfo = DB::table('dealer_informations')
            ->select('dealer_name','dealer_address','zone','dealer_phone_number')
            ->where('dealer_code',$finalDealerCode)
            ->orWhere('alternate_code',$finalDealerCode)
            ->first();

            $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerStock"),$dPhone,$finalDealerCode),"GET");
            $responseData       = json_decode($getCurlResponse['response_data'],true);

            Log::info('Load Retailer Stock');
            return view('admin.stock.retailer_stock_list',compact('responseData','dealerInfo'));
        } else {
            Log::warning('Invalid Request Retailer Stock');
            return redirect()->back()->with('error');
        }
    }
    
    public function salesReturn($orderId)
    {
        $orderIMEI = DB::table('sale_products')
        ->where('sales_id',$orderId)
        ->get(['ime_number']);

        $DelarDistributionModel = new DelarDistribution;
        $DelarDistributionModel->setConnection('mysql2');

        foreach($orderIMEI as $imei) {
            $DelarDistributionModel::where('barcode',$imei->ime_number)
            ->orWhere('barcode2',$imei->ime_number)
            ->update([
                "status"=> 1,
            ]);
        }

        $orderStatus = DB::table('sales')->where('id',$orderId)->delete();
        if($orderStatus)
        {
            $deleteProducts = DB::table('sale_products')
            ->where('sales_id',$orderId)
            ->delete();
            
            if($deleteProducts) {
                return response()->json('success');
            }
        }
        return response()->json('error');
    }
    
    public function getIMEIdisputeNumber(Request $request)
    {
        $imeiDisputeList = DB::table('imei_disputes')->paginate(100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $imeiDisputeList = DB::table('imei_disputes')
                ->where('id',$query)
                ->orWhere('imei_number','like', '%'.$query.'%')
                ->orWhere('description', 'like', '%'.$query.'%')
                ->orWhere('comments', 'like', '%'.$query.'%')
                ->orWhere('date', 'like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.imei_dispute.result_data', compact('imeiDisputeList'))->render();
        }

        if(isset($imeiDisputeList) && $imeiDisputeList->isNotEmpty()) {
            Log::info('Load IMEI Dispute List');
        } else {
            Log::warning('IMEI Dispute List Not Found');
        }
        return view('admin.imei_dispute.list',compact('imeiDisputeList'));
    }

    public function editIMEIdisputeNumber($id)
    {
        $imeiDisputeNumberInfo = DB::table('imei_disputes')->where('id','=',$id)->first();

        if(isset($imeiDisputeNumberInfo) && !empty($imeiDisputeNumberInfo)){
            return response()->json(['status'=>'success','imeidisputeInfo'=>$imeiDisputeNumberInfo]);
        }
        return response()->json('error');
    }

    public function updateIMEIdisputeNumber(Request $request)
    {
        $comments               = $request->input('comments');
        $imeiDisputeNumber      = $request->input('imei_number');
        $imeiDisputeId          = $request->input('imei_id');

        if(isset($imeiDisputeId) && $imeiDisputeId > 0){
            $updateStatus = DB::table('imei_disputes')
            ->where('id','=',$imeiDisputeId)
            ->update([
                "status"=> $request->input('status'),
                "comments"=> $request->input('comments'),
                "updated_at"=>Carbon::now()
            ]);

            if($updateStatus){
                return response()->json('success');
            }
        }

        return response()->json('error');
    }
    
    public function getAllPendingOrder()
    {
        $saleList = DB::table('sales')
        ->where('status',1)
        ->get();

        foreach($saleList as $sale)
        {
            $saleProductList = DB::table('sale_products')
            ->select('*')
            ->where('sales_id',$sale->id)
            ->get();

            $dealerInfo = DB::table('dealer_informations')
            ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
            ->where('dealer_code',$sale->dealer_code)
            ->orWhere('alternate_code',$sale->dealer_code)
            ->first();

            foreach ($saleProductList as $saleProduct) {
                $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
              //  $sale->product_list = $saleProduct;
            }


            $sale->product_list=$saleProductList;


            $retailerInfo = DB::table('retailers')
            ->select('retailer_name as name','retailder_address as address','phone_number as phone')
            ->where('retailer_id',$sale->retailer_id)
            ->first();

            $brandPromoterInfo = DB::table('brand_promoters')
            ->select('bp_name as name','bp_phone as phone')
            ->where('bp_id',$sale->bp_id)
            ->first();

            $sale->retailer_info = $retailerInfo;
            $sale->bp_info = $brandPromoterInfo;
            
        }

        return view('admin.report.pending_sales',compact('saleList'));
    }

    public function PendingOrderStatus($orderId)
    {
        
        $StatusInfo = DB::table('sales')->where('id',$orderId)->value('status');
        $old_status = $StatusInfo;


        $UpdateStatus = $old_status == 1 ? 0 : 1;

        $Update = DB::table('sales')
        ->where('id',$orderId)
        ->update([
            "status"=>$UpdateStatus ? $UpdateStatus:0
        ]);

        if($Update) {
            Log::info('Order Status Updated Successfully');
            return response()->json(['success'=>'Status change successfully.']);
        } else {
            Log::error('Order Status Updated Failed');
            return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }

    public function getAllPendingMessage()
    {
        $MessageList = \DB::table('authority_messages as tab1')
        ->select('tab1.*')
        ->leftJoin('authority_messages as tab2','tab2.reply_for','=','tab1.id')
        ->where('tab1.reply_for','=',0)
        ->whereNull('tab2.reply_for')
        ->orderBy('tab1.id','asc')
        ->paginate(10);

        return view('admin.message.list',compact('MessageList'));
    }
    
    public function getAllPendingLeave(Request $request)
    {
        /*$leaveList = DB::table('view_bp_leave_report')
        ->where('status','=','Pending')
        ->paginate(100);

        return view('admin.report.bp_pending_leave',compact('leaveList'));*/

        $leaveList = DB::table('view_bp_leave_report')
                    ->where('status','=','Pending')
                    ->paginate(10);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $leaveList = DB::table('view_bp_leave_report')
                ->where('id',$query)
                ->where('status','=','Pending')
                ->orWhere('bp_name','like', '%'.$query.'%')
                ->orWhere('leave_type', 'like', '%'.$query.'%')
                ->orWhere('apply_date', 'like', '%'.$query.'%')
                ->orWhere('start_date', 'like', '%'.$query.'%')
                ->orWhere('total_day', 'like', '%'.$query.'%')
                ->orWhere('start_time', 'like', '%'.$query.'%')
                ->orWhere('reason', 'like', '%'.$query.'%')
                ->orWhere('status','=','Pending')
                ->orderBy($sort_by, $sort_type)
                ->paginate(10);

            return view('admin.report.bp_pending_leave_result_data', compact('leaveList'))->render();
        }
        return view('admin.report.bp_pending_leave_list',compact('leaveList'));
    }

    public function getPreBookingOrderList(Request $request)
    {
        $month_Sdate       =  date('Y-m-01');
        $month_Edate       =  date('Y-m-t');

        $preBookingOrderList =  DB::table('prebooking_orders')
        ->select('customer_name','customer_phone','customer_address','model','color','qty','advanced_payment','booking_date')
        ->selectRaw('count(qty) as bookingQty')
        ->whereBetween('booking_date',[$month_Sdate,$month_Edate])
        ->groupBy('model')
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $preBookingOrderList = DB::table('prebooking_orders')
                ->where('id',$query)
                ->orWhere('model', 'like', '%'.$query.'%')
                ->orWhere('color', 'like', '%'.$query.'%')
                ->orWhere('qty',$query)
                ->orderBy($sort_by, $sort_type)
                ->groupBy('model')
                ->paginate(100);

            return view('admin.report.prebooking_order_result_data', compact('preBookingOrderList'))->render();
        }
        return view('admin.report.prebooking_order_report',compact('preBookingOrderList'));
    }

    public function preOrderReportDetails($model)
    {
        //echo $model;exit();
        $month_Sdate       =  date('Y-m-01');
        $month_Edate       =  date('Y-m-t');

        $salesInfoList = DB::table('prebooking_orders')
        ->select('prebooking_orders.*','brand_promoters.bp_name','retailers.retailer_name')
        ->leftJoin("brand_promoters", "brand_promoters.id", "=", "prebooking_orders.bp_id")
        ->leftJoin("retailers", "retailers.id", "=", "prebooking_orders.retailer_id")
        ->where('prebooking_orders.model','like','%'.$model.'%')
        //->whereBetween('booking_date',[$month_Sdate,$month_Edate])
        ->get();

        $viewProductInfo = [];
        foreach($salesInfoList as $salesInfo)
        {
             $viewProductInfo[] = "<tr><td>".$salesInfo->customer_name."</td><td>".$salesInfo->customer_phone."</td><td>".$salesInfo->customer_address."</td><td>".$salesInfo->model."</td><td>".$salesInfo->color."</td><td>".$salesInfo->qty."</td><td>".$salesInfo->advanced_payment."</td><td>".$salesInfo->booking_date."</td><td>".$salesInfo->bp_name."</td><td>".$salesInfo->retailer_name."</td></tr>";
        }

        if($viewProductInfo) {
            return response()->json(['itemList'=>$viewProductInfo]);
        } else {
             return response()->json('error');
        }
    }
    
    public function preBookingReport(Request $request)
    {
        $bpId       = 0;
        $retailId   = 0;
        $sellerName = "";
        if($request->input('bp_id')) {
            $bpId           = $request->input('bp_id');
            $sellerName     = BrandPromoter::where('status','=',1)
                            ->where('id','=',$bpId)
                            ->value('bp_name');
        } else {
            $retailId       = $request->input('retailer_id');
            $sellerName     = Retailer::where('status','=',1)
                            ->where('id','=',$retailId)
                            ->value('retailer_name');
        }

        $start_date =  $request->input('start_date');
        $end_date   =  $request->input('end_date');

        $preBookingOrderList = DB::table('prebooking_orders')
        ->select('customer_name','customer_phone','customer_address','model','color','qty','advanced_payment','booking_date')
        ->selectRaw('count(qty) as bookingQty')
        ->orWhere(function($query) use($start_date, $end_date, $bpId, $retailId) {
            if($bpId > 0) {
                $query->where('bp_id',$bpId);
            }
            else if($retailId > 0) {
                $query->where('retailer_id',$retailId);
            }
            else if($start_date && $end_date) {
                $query->whereBetween('booking_date',[$start_date,$end_date]);
            }
        })
        ->groupBy('model')
        ->paginate(100);

        return view('admin.report.prebooking_order_report',compact('preBookingOrderList','sellerName'));
    }

    public function getPendingOrderList(Request $request)
    {
        $bpList         = BrandPromoter::get(['bp_id','bp_name']);
        $retailerList   = Retailer::get(['retailer_id','retailer_name']);

        $month_Sdate    =  date('Y-m-01');
        $month_Edate    =  date('Y-m-t');

        
        $saleList = DB::table('sales')
        ->whereIn('status',[1,2])
        ->whereBetween('sale_date',[$month_Sdate,$month_Edate])
        ->orderBy('id','desc')
        ->paginate(100);

        foreach($saleList as $sale)
        {
            $saleProductList = DB::table('sale_products')
            ->select('*')
            ->where('product_status','=',1)
            ->where('sales_id',$sale->id)
            ->get();

            $dealerInfo = DB::table('dealer_informations')
            ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
            ->where('dealer_code',$sale->dealer_code)
            ->orWhere('alternate_code',$sale->dealer_code)
            ->first();

            foreach ($saleProductList as $saleProduct) {
                $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
              //  $sale->product_list = $saleProduct;
            }


            $sale->product_list=$saleProductList;


            $retailerInfo = DB::table('retailers')
            ->select('retailer_name as name','retailder_address as address','phone_number as phone')
            ->where('retailer_id',$sale->retailer_id)
            ->first();

            $brandPromoterInfo = DB::table('brand_promoters')
            ->select('bp_name as name','bp_phone as phone')
            ->where('bp_id',$sale->bp_id)
            ->first();

            $sale->retailer_info = $retailerInfo;
            $sale->bp_info = $brandPromoterInfo;
        }

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $saleList = DB::table('sales')
                ->whereIn('status',[1,2])
                ->whereBetween('sale_date',[$month_Sdate,$month_Edate])
                ->orWhere('zone_name','like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.report.pending_sales_report_result_data', compact('saleList'))->render();
        }

        if(isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Order Pending Sale List');
        } else {
            Log::warning('Order Pending Sales List Not Found');
        }
        return view('admin.report.pending_sales_report',compact('saleList'));
    }
    
    public function searchPendingOrderList(Request $request)
    {
        $bpId       = 0;
        $retailId   = 0;
        $sellerName = "";
        if($request->input('bp_id')) {
            $bpId           = $request->input('bp_id');
            $sellerName     = BrandPromoter::where('status','=',1)
                            ->where('id','=',$bpId)
                            ->value('bp_name');
        } else {
            $retailId       = $request->input('retailer_id');
            $sellerName     = Retailer::where('status','=',1)
                            ->where('id','=',$retailId)
                            ->value('retailer_name');
        }

        $start_date =  $request->input('start_date');
        $end_date   =  $request->input('end_date');

        $saleList = DB::table('sales')
        ->whereIn('status',[1,2])
        ->whereBetween('sale_date',[$start_date,$end_date])
        ->orderBy('id','desc')
        ->paginate(100);

        foreach($saleList as $sale)
        {
            $saleProductList = DB::table('sale_products')
            ->select('*')
            ->where('product_status','=',1)
            ->where('sales_id',$sale->id)
            ->get();

            $dealerInfo = DB::table('dealer_informations')
            ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
            ->where('dealer_code',$sale->dealer_code)
            ->orWhere('alternate_code',$sale->dealer_code)
            ->first();

            foreach ($saleProductList as $saleProduct) {
                $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
              //  $sale->product_list = $saleProduct;
            }


            $sale->product_list=$saleProductList;


            $retailerInfo = DB::table('retailers')
            ->select('retailer_name as name','retailder_address as address','phone_number as phone')
            ->where('retailer_id',$sale->retailer_id)
            ->first();

            $brandPromoterInfo = DB::table('brand_promoters')
            ->select('bp_name as name','bp_phone as phone')
            ->where('bp_id',$sale->bp_id)
            ->first();

            $sale->retailer_info = $retailerInfo;
            $sale->bp_info = $brandPromoterInfo;
        }


        if(isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Order Pending Sale List');
        } else {
            Log::warning('Order Pending Sales List Not Found');
        }
        return view('admin.report.pending_sales_report',compact('saleList','sellerName'));
    }

    public function pendingOrderStatusUpdate(Request $request)
    {
        $status     = $request->input('status');
        $comments   = $request->input('comments');
        $orderId    = $request->input('pending_order_id');

        $responseStatus = 0;

        if($orderId > 0) 
        {
            $changeOrderStatus = DB::table('sales')
            ->where('id','=',$orderId)
            ->update([
                "status"=>$status,
                "note"=>$comments
            ]);

            if($status == 0) {
                DB::table('sale_products')
                ->where('sales_id','=',$orderId)
                ->update([
                    "product_status"=>$status,
                ]);
            }
            $responseStatus = 1;
        }

        if($responseStatus == 1) {
            return response()->json('success');
        }
        return response()->json('error');
    }

    public function bpSalesReportForm(Request $request)
    {
        $bpSalesList = DB::table('view_sales_reports')
        ->select('bp_id','bp_name','bp_phone',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->where('bp_id','>',0)
        ->orderBy('total_sale_amount','DESC')
        ->groupBy('bp_id')
        ->paginate(100);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $bpSalesList = DB::table('view_sales_reports')
            ->select('bp_id','bp_name','bp_phone',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
            ->where('bp_id','>',0)
            ->orWhere('bp_name','like', '%'.$query.'%')
            ->orWhere('bp_phone','like', '%'.$query.'%')
            ->orWhere('sale_qty','=',$query)
            ->orderBy('msrp_price','DESC')
            ->orderBy($sort_by, $sort_type)
            ->groupBy('bp_id')
            ->paginate(100);
            return view('admin.report.bp_result_data', compact('bpSalesList'))->render();
        }

        if(isset($bpSalesList) && $bpSalesList->isNotEmpty()) {
            Log::info('Load Bp Sales List');
        } else {
            Log::warning('Bp Sales List Not Found');
        }
        return view('admin.report.bp_sales_report',compact('bpSalesList'));
    }

    public function bpDateRangesalesReport(Request $request)
    {
        $ordBy = "DESC";
        if(isset($ordBy) && !empty($ordBy)) {
            $ordBy = $request->input('order_by');
        }

        $bpId      = 0;
        if($request->input('bp_id')) {
            $bpId  = $request->input('bp_id');
        }

        $start_date     =  $request->input('start_date');
        $end_date       =  $request->input('end_date');
        $currentSdate       = date('Y-m-d');
        $before3MonthSdate  = date('Y-m-d',strtotime("-3 months",strtotime($currentSdate)));

        $reqSdate = "";
        $reqEdate = "";
        if(!empty($start_date) && strtotime($start_date) >= strtotime($before3MonthSdate) && strtotime($start_date) <= strtotime($currentSdate)) {
            $reqSdate = $start_date." 00:00:00";
        } else {
            $reqSdate = $before3MonthSdate;
        }

        if(!empty($end_date) && strtotime($end_date) >= strtotime($before3MonthSdate) && strtotime($end_date) <= strtotime($currentSdate)) {
            $reqEdate = $end_date." 23:59:59";
        } else {
            $reqEdate = $currentSdate." 23:59:59";
        }

        $bpSalesList = "";
        if ( !empty( $request->except('_token') ) ) 
        {
            $bpSalesList = DB::table('view_sales_reports')
            ->select('bp_id','bp_name','bp_phone',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
            ->where('bp_id','>',0)
            ->when($bpId, function ($query, $bpId) {
                return $query->where('bp_id','=',$bpId);
            })
            ->when($reqSdate, function ($query, $reqSdate) {
                return $query->where('sale_date','>=', $reqSdate);
            })
            ->when($reqEdate, function ($query, $reqEdate) {
                return $query->where('sale_date','<=', $reqEdate);
            })
            ->orderBy('total_sale_amount',$ordBy)
            ->groupBy('bp_id')
            ->paginate(100);
        }

        if($bpSalesList) {
            return view('admin.report.bp_sales_report',compact('bpSalesList'))->with('success','Sales Data Found');
        }
        return view('admin.report.bp_sales_report',compact('bpSalesList'))->with('error','Sales Data Not Found');
    }

    public function BpOrderDetailsView($bpId)
    {
        $salesInfo = DB::table('view_sales_reports')
        ->where('bp_id',$bpId)
        ->first();

        $saleProductList = DB::table('view_sales_reports')
        ->select('product_type','product_model','product_color','msrp_price',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->where('bp_id',$bpId)
        ->groupBy('product_model')
        ->groupBy('product_color')
        ->get();

        $viewSalesInfo = "<tbody><tr><th scope='row'>BP Name:</th><td>".$salesInfo->bp_name."</td></tr><tr><th scope='row'>BP Phone:</th><td>".$salesInfo->bp_phone."</td></tr></tbody>";

        $viewItemList = [];
        $i=1;
        foreach($saleProductList as $row) {
            $viewItemList[] = "<tr><td>".$i.".</td><td>".$row->product_type."</td><td>".$row->product_model."</td><td>".$row->product_color."</td><td>".number_format($row->msrp_price,2)."</td><td>".$row->total_qty."</td><td>".number_format($row->total_sale_amount,2)."</td></tr>";
        
        $i++;
        }

        if($viewSalesInfo) {
            Log::info('Load BP Sales Info');
            return response()->json(['salesInfo'=>$viewSalesInfo,'itemList'=>$viewItemList]);
        } else {
            Log::warning('BP Sales Info Not Found');
             return response()->json('error');
        }
    }
    
    public function focus_model_to_bp_employee_stock_check(Request $request)
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

    public function employeeStockCheck(Request $request)
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

    function stockReportSendMail($sendEmail,$rowDataList)
    {
        Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList], function($message) use ($rowDataList) {
        $message->to($sendEmail);
        //$message->to('sayed.giantssoft@gmail.com');
        $message->subject('Stock Update');
        $message->from('demoadmin@manush.co.uk','Retail Gear');
        });
    }
}
