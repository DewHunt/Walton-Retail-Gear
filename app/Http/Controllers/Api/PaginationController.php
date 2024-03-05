<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PaginationController extends Controller
{
    function index(Request $request)
    {
        $data = DB::table('dealer_informations')->orderBy('id', 'asc')->paginate(5);
        if($request->ajax()) {

            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $data = DB::table('dealer_informations')
                ->where('id',$query)
                ->orWhere('dealer_code',$query)
                ->orWhere('dealer_name', 'like', '%'.$query.'%')
                ->orWhere('zone', 'like', '%'.$query.'%')
                ->orWhere('dealer_phone_number', 'like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(5);
            return view('pagination_data', compact('data'))->render();

        }
        return view('pagination', compact('data'));
    }

    function fetch_data(Request $request)
    {
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            // $data = DB::table('dealer_informations')
            //     ->where('id', 'like', '%'.$query.'%')
            //     ->orWhere('dealer_code', 'like', '%'.$query.'%')
            //     ->orWhere('dealer_name', 'like', '%'.$query.'%')
            //     ->orWhere('zone', 'like', '%'.$query.'%')
            //     ->orWhere('dealer_phone_number', 'like', '%'.$query.'%')
            //     ->orderBy($sort_by, $sort_type)
            //     ->paginate(100);

             $data = DB::table('dealer_informations')
                ->where('id',$query)
                ->orWhere('dealer_code',$query)
                ->orWhere('dealer_name', 'like', '%'.$query.'%')
                ->orWhere('zone', 'like', '%'.$query.'%')
                ->orWhere('dealer_phone_number', 'like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('pagination_data', compact('data'))->render();
        }
    }
}