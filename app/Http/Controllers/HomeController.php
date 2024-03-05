<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use App\Models\Sale;
use App\Models\DealerInformation;
use App\Models\LoginActivity;
use App\Repositories\HomeInterface;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $model;
    public function __construct(HomeInterface $homeRepo,DealerInformation $dealer_information,Zone $zone)
    {
    	$this->middleware('auth');
        $this->home     = $homeRepo;
        $this->model    = new Repository($dealer_information);
        //$this->model    = new Repository($zone);
    }
    
    public function index()
    {
        //Current Month Daily Sales Report Start
        $month_Sdate    =  date('Y-m-01');
        $month_Edate    =  date('Y-m-t');

        $monthlySalesList = DB::table('view_sales_reports')
        ->select(
        DB::raw("Date(sale_date) as date"),
        DB::raw("SUM(sale_qty) as total_qty"),
        DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->whereBetween('sale_date',[$month_Sdate,$month_Edate])
        ->orderBy('sale_date','ASC')
        ->groupBy(DB::raw("DATE(sale_date)"))
        ->get();

        $sales_result[] = ['Date','Qty','Amount'];
        $salesDate      = [];
		$salesQty       = [];
		$salesAmount    = [];
        foreach ($monthlySalesList as $key => $value) {
            $sales_result[++$key] = [$value->date, (int)$value->total_qty, (int)$value->total_sale_amount];

            $salesDate[]      = [$value->date];
            $salesQty[]       = [(int)$value->total_qty];
            $salesAmount[]    = [(int)$value->total_sale_amount];
        }

        for($i = 1; $i <=  date('t'); $i++) {
           $getCurrentMonthAlldates[] = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        //Current Month Daily Sales Report End

        //BP Sales Report Start
        $bpTopSalerList = DB::table('view_sales_reports')
        ->select('bp_name',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->where('bp_id','>',0)
        ->orderBy('total_sale_amount','DESC')
        ->groupBy('bp_id')
        ->limit(15)
        ->get();
        
        $bpName     = [];
        $bpQty      = [];
        $bpAmount   = [];
        
        if($bpTopSalerList->isNotEmpty()) {
            foreach($bpTopSalerList as $row) {
                $bpName[]   = str_replace(array( '\'', '"',',' , ';', '<', '>','.' ), ' ', $row->bp_name);
                $bpQty[]    = $row->total_qty;
                $bpAmount[] = $row->total_sale_amount;
            }
        }
        //BP Top Saler Report End

        //Retailer Top Saler Report Start
        $RetailerTopSalerList = DB::table('view_sales_reports')
        ->select('retailer_name',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->where('retailer_id','>',0)
        ->orderBy('total_sale_amount','DESC')
        ->groupBy('retailer_id')
        ->limit(15)
        ->get();
        
        $retailerName   = [];
        $retailerQty    = [];
        $retailerAmount = [];
        
        if($RetailerTopSalerList->isNotEmpty()) {
            foreach($RetailerTopSalerList as $row) {
                $retailerName[]   = str_replace(array( '\'', '"',',' , ';', '<', '>','.' ), ' ', $row->retailer_name);
                $retailerQty[]    = $row->total_qty;
                $retailerAmount[] = $row->total_sale_amount;
            }
        }
        //Retailer Top Saler Report End

        //Product Model Waise Sales Report Pie Chart Start
        $modelWaiseSalesList = DB::table('view_sales_reports')
        ->select('product_model', \DB::raw("COUNT('sale_qty') as totQty"))
        ->where('product_master_id','>',0)
        ->orderBy('totQty','DESC')
        ->groupBy('product_model')
        ->limit(15)
        ->get();
        //Product Model Waise Sales Report Pie Chart End

        //Current Year Monthly Sales Report Start
        $yearMonthSalesQtyList = DB::table('view_sales_reports')
        ->select(\DB::raw("MONTHNAME(sale_date) as monthName"),\DB::raw("COUNT('sale_qty') as totQty"),DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->whereYear('sale_date', date('Y'))
        ->orderBy(\DB::raw("Month(sale_date)"),'ASC')
        ->groupBy(\DB::raw("Month(sale_date)"))
        ->get();
        
        $yearMonthNameList = [];
        $yearMonthQty      = [];
        foreach($yearMonthSalesQtyList as $row) {
            $yearMonthNameList[] = $row->monthName;
            $yearMonthQty[]      = (int)$row->totQty;
        }
        //Current Year Monthly Sales Report End

        /*$loginLogList = DB::table('login_activities as lc')
        ->select('lc.type','lc.created_at','lc.user_agent','lc.ip_address','users.name as name')
        ->leftJoin('users','users.id', '=', 'lc.user_id')
        //->groupBy('login_activities.user_id')
        ->orderBy('lc.id','desc')
        ->limit(10)
        ->get();*/
        
        $loginLogList = DB::table('view_user_login_activity')
        ->orderBy('created_at','desc')
        ->limit(10)
        ->get();
        
        
        return view('admin.home')->with('salesDate',json_encode($salesDate,JSON_NUMERIC_CHECK))->with('salesQty',json_encode($salesQty,JSON_NUMERIC_CHECK))->with('bpName',json_encode($bpName,JSON_NUMERIC_CHECK))->with('bpAmount',json_encode($bpAmount,JSON_NUMERIC_CHECK))->with('retailerName',json_encode($retailerName,JSON_NUMERIC_CHECK))->with('retailerAmount',json_encode($retailerAmount,JSON_NUMERIC_CHECK))->with('modelWaiseSalesList',$modelWaiseSalesList)->with('yearMonthNameList',json_encode($yearMonthNameList,JSON_NUMERIC_CHECK))->with('yearMonthQty',json_encode($yearMonthQty,JSON_NUMERIC_CHECK))->with('loginLogList',$loginLogList);
    }

    /*
    //Note: If you are calling any methods and the method not in repository then you can get just using ‘getModel’ just like below.
    public function index()
    {
        $posts = $this->dealer_information->getModel()->orderBy('id', 'desc')->get();
        return response()->json($posts);
    }
    */

    public function store(Request $request)
    {
       return $this->model->create($request->all());
    }

    public function show($id)
    {
       return $this->model->show($id);
    }

    public function update(Request $request, $id)
    {
       $this->model->update($request->all(), $id);
    }

    public function destroy($id)
    {
       return $this->model->delete($id);
    }
}
