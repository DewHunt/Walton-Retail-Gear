<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerInformation;
use App\Models\Retailer;
use App\Models\Zone;
use Carbon\Carbon;
use DB;
use Response;
use Redirect;
use Session;

class SearchController extends Controller
{
    
    public function SearchRetailer(Request $request)
    {
    	$oldZoneIds         = Session::get('zoneIds');
        $search             = $request->search;
    	

    	if(isset($oldZoneIds)) {
            $eraseableZones     = array_diff($search,$oldZoneIds);
            if ($eraseableZones) {
                //return response()->json($eraseableZones);
                $search = $eraseableZones;
            }
        }

    	Session::put('zoneIds', $request->search);
    	$retailerList 	    = "";
        if(is_array($search)) {
        	//$retailerList   = Retailer::whereIn('zone_id',$arrayDifferent)
        	$retailerList   = Retailer::whereIn('zone_id',$search)
        	->get(['id','retailer_name','phone_number']);
        }

        Session::put('retailerListingArray', $retailerList);
        $getAllRetailerList         = Session::get('retailerListingArray');

        //dd($getAllRetailerList);

        /*
        $response = array();
        foreach($retailerList as $row) {
            $response[] 	= '<option value="'.$row->id.'">'.$row->retailer_name.'['.$row->phone_number.']</option>';
        }
        */

        $response = array();
        foreach($getAllRetailerList as $row) {
            $response[]     = '<option value="'.$row->id.'">'.$row->retailer_name.'['.$row->phone_number.']</option>';
        }
        return response()->json($response);
    }
}
