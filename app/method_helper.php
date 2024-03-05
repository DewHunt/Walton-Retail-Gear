<?php
if(!function_exists('getTableWhere')) {
 
    function getTableWhere($table,$where) {
        $data = \DB::table($table)
            ->select(\DB::raw('*'))
            ->where($where)
            ->first();
        return $data;
    }
}

if(!function_exists('_getTableWhere')) {
 
    function _getTableWhere($table) {
        $data = \DB::table($table)
            ->select(\DB::raw('*'));
        return $data;
    }
}

if(!function_exists('allPendingNotification')) {
	function allPendingNotification() {

		$total_pending_order = \DB::table('sales')
        ->where('status',1)
        ->count();

        /*
        $total_pending_message = \DB::table('authority_messages')
        ->where('status',0)
        ->where('reply_for',0)
        ->where('who_reply',0)
        ->count();
        */

	    $total_pending_message = \DB::table('authority_messages as tab1')
    	->select('tab1.*')
    	->leftJoin('authority_messages as tab2','tab2.reply_for','=','tab1.id')
    	->where('tab1.reply_for','=',0)
    	->whereNull('tab2.reply_for')
    	->orderBy('tab1.id','asc')
    	->count();

        //->count();
        /*
		SELECT `tab1`.* 
		FROM `authority_messages` AS `tab1` 
		LEFT JOIN `authority_messages` AS `tab2` ON `tab2`.`reply_for` = `tab1`.`id`
		WHERE `tab2`.`reply_for` IS NULL AND `tab1`.`reply_for` = 0
		ORDER BY `id` ASC
		*/

        $total_dispute_imei = \DB::table('imei_disputes')
        ->where('status',0)
        ->count();
        
        $total_pending_leave = \DB::table('bp_leaves')
        ->where('status','=','Pending')
        ->count();

        $totalNotification = $total_pending_order + $total_pending_message + $total_dispute_imei + $total_pending_leave;
        $responseArray = ["totalNotification"=>$totalNotification,"pending_order"=>$total_pending_order,"pending_message"=>$total_pending_message,"dispute_imei"=>$total_dispute_imei,'pending_leave'=>$total_pending_leave];
		
		return $responseArray;
	}
}

if(!function_exists('GetTableWithPagination')) {
    function RequestApiUrl($api_url_name) {
	
    	$url = "";	
    	if($api_url_name == "EmployeeId"){
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/Employee?empId=%s";
    	}
    	elseif($api_url_name == "DealerDistribution") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/DealerDistribution?startDate=%s&endDate=%s";
    	}
    	elseif($api_url_name == "ProductAll") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/Product";
    	}
    	elseif($api_url_name == "ProducId" || $api_url_name == "ProducModel") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/Product/%s";
    	}
    	elseif($api_url_name == "ZoneAll") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/Zone";
    	}
    	elseif($api_url_name == "ZoneId") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/Zone/%s";
    	}
    	elseif($api_url_name == "RetailerAll") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/RetailerInfo";
    	}
    	elseif($api_url_name == "RetailerId") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/RetailerInfo/GetRetailerInfoById/%s";
    	}
    	elseif($api_url_name == "RetailerPhone") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/RetailerInfo/GetRetailerInfoByPhone/%s";
    	}
    	elseif($api_url_name == "DealerCode") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/Distributors/GetADistributorByDealerCode/%s";
    	}
    	elseif($api_url_name == "DealerAll") {
    		$url = "http://mapi.waltonbd.com/outsourceapi/api/distributors/getalldistributors";
    	}
    	elseif($api_url_name == "BPromoterPhone") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/RetailerInfo/GetRetailerInfoByPhone/%s"; //Api Ekhono Dei Nai.
    	}
    	elseif($api_url_name == "GetRetailerStock") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/WDS/GetRetailerStock?PhoneNumber=%s&DealerCode=%s";
    	}
    	elseif($api_url_name == "GetRetailerLiftingIncentive") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/RetailerInfo/GetRetailerIncentive?startDate=%s&endDate=%s&phoneno=%s";
    	}
    	elseif($api_url_name == "GetStock") {
    		$url = "http://mapi.waltonbd.com/OutSourceApi/api/WDS/RetailerStock?Id=%s&clientType=%s";
    	}
    	elseif($api_url_name == "GetIMEIinfo") {
    		$url = "http://mapi.waltonbd.com/outSourceApi/api/WDS/ImeiWiseInformation?IMEI=%s";
    	}
        elseif($api_url_name == "UpdateIMEIStatus") {
            $url = "http://mapi.waltonbd.com/outSourceApi/api/WDS/ImeiWiseInformationUpdate?IMEI=%s";
        }
    	return $url;
    }
}


if(!function_exists('GetTableWithPagination')) {
	function GetTableWithPagination($table_name,$limit){
		$data = \DB::table($table_name)->paginate($limit);
		return $data;
	}
}

if(!function_exists('ViewTableListWhere')) {
	function ViewTableListWhere($table_name,$mobileNumber){
		$data = \DB::table($table_name)
		->where('employee_phone',$mobileNumber)
		->orWhere('brand_promoter_phone',$mobileNumber)
        ->orWhere('retailer_phone',$mobileNumber)
		->first();
		return $data;
	}
}

if(!function_exists('ViewTableList')) {
	function ViewTableList($table_name)
	{
		$data = \DB::table($table_name)
		->get();
		return $data;
	}
}


/*
* 200: This code is used for a successful request.
*
* 201: For a successful request and data was created.
*
* 204: For empty response.
*
* 400: This is used for Bad Request. If you enter something wrong or you missed some required parameters, 
* then the request would not be understood by the server, and you will get 400 status code.
*
* 401: This is used for Unauthorized Access. If the request authentication failed or the user does not 
* have permissions for the requested operations, then you will get a 401 status code.
*
* 403: This is for Forbidden or Access Denied.
*
* 404: This will come if the Data Not Found.
*
* 405: This will come if the method not allowed or if the requested method is not supported.
*
* 500: This code is used for Internal Server Error.
*
* 503: And this code is used for Service Unavailable.
*
* 301: When ime number not found.
*
* 302: Any Kind Of Custome Message.
*/

if(!function_exists('_apiResponses')) {
	function _apiResponses($responseCode,$object_result=null,$array_result=null,$errorMsg=null) {

		$response = "";	
		if($responseCode == 200) {
			$response =[
				'message'=>'success'
			];
		}
		else if($responseCode == 201 && !empty($object_result)) {
			//echo "<pre>";print_r($array_result);exit();
			if($array_result){
				$response =[
					$object_result,
					"not found"=>$array_result
				];
			}
			else{
				$response =[
					$object_result
				];
			}
			
		}
		else if($responseCode == 203) {
            $response =[
				'message'=>$errorMsg,
				'code'=>203
			];
		}
		else if($responseCode == 204) {
			$response =[
				'message'=>'success',
				'code'=>204
			];
		}
		else if($responseCode == 400) {
			$response =[
				'message'=>'Bad Request',
				'code'=>400
			];
		}
		else if($responseCode == 401) {
			$response =[
				'message'=>'Unauthorized Access',
				'code'=>401
			];
		}
		else if($responseCode == 403) {
			$response =[
				'message'=>'This is for Forbidden or Access Denied',
				'code'=>403

			];
		}
		else if($responseCode == 404) {
			$response =[
				'message'=>'Data Not Found',
				'code'=>404
			];
		}
		else if($responseCode == 405) {
			$response =[
				'message'=>'Method not allowed',
				'code'=>405
			];
		}
		else if($responseCode == 500) {
			$response =[
				'message'=>'Internal Server Error',
				'code'=>500
			];
		}
		else if($responseCode == 503) {
			$response =[
				'message'=>'Service Unavailable',
				'code'=>503
			];
		}
		else if($responseCode == 301 && !empty($object_result)) {
			$response =[
				'message'=>'Ime Not Found.Please Contact Your Authority',
				'code'=>301,
				"not_found_ime"=>$object_result
			];
		}
		else if($responseCode == 302) {
			$response =[
				'message'=>$errorMsg,
				'code'=>302,
			];
		}
		else if($responseCode == 422) {
			$response =[
				'message'=>$errorMsg,
				'code'=>422,
			];
		}


		return $response;
	}
}

if(!function_exists('apiResponses')) {

	function apiResponses($responseCode,$errorMsg=null)
	{
		$responseArray = [
            ['code'=>100, 'message'=>'Continue'],
            ['code'=>101, 'message'=>'Switching Protocols'],
            ['code'=>102, 'message'=>'Processing'],
            ['code'=>200, 'message'=>'OK'],
            ['code'=>201, 'message'=>'Created'],
            ['code'=>202, 'message'=>'Accepted'],
            ['code'=>203, 'message'=>'Non-Authoritative Information'],
            ['code'=>204, 'message'=>'No Content'],
            ['code'=>205, 'message'=>'Reset Content'],
            ['code'=>206, 'message'=>'Partial Content'],
            ['code'=>207, 'message'=>'Multi-Status'],
            ['code'=>208, 'message'=>'Already Reported'],
            ['code'=>226, 'message'=>'IM Used'],
            ['code'=>300, 'message'=>'Multiple Choices'],
            ['code'=>301, 'message'=>'Moved Permanently'],
            ['code'=>302, 'message'=>'Found'],
            ['code'=>303, 'message'=>'See Other'],
            ['code'=>304, 'message'=>'Not Modified'],
            ['code'=>305, 'message'=>'Use Proxy'],
            ['code'=>307, 'message'=>'Temporary Redirect'],
            ['code'=>308, 'message'=>'Permanent Redirect'],
            ['code'=>400, 'message'=>'Bad Request'],
            ['code'=>401, 'message'=>'Unauthorized'],
            ['code'=>402, 'message'=>'Payment Required'],
            ['code'=>403, 'message'=>'Forbidden'],
            ['code'=>404, 'message'=>'Not Found'],
            ['code'=>405, 'message'=>'Method Not Allowed'],
            ['code'=>406, 'message'=>'Not Acceptable'],
            ['code'=>407, 'message'=>'Proxy Authentication Required'],
            ['code'=>408, 'message'=>'Request Timeout'],
            ['code'=>409, 'message'=>'Conflict'],
            ['code'=>410, 'message'=>'Gone'],
            ['code'=>411, 'message'=>'Length Required'],
            ['code'=>412, 'message'=>'Precondition Failed'],
            ['code'=>413, 'message'=>'Payload Too Large'],
            ['code'=>414, 'message'=>'URI Too Long'],
            ['code'=>415, 'message'=>'Unsupported Media Type'],
            ['code'=>416, 'message'=>'Range Not Satisfiable'],
            ['code'=>417, 'message'=>'Expectation Failed'],
            ['code'=>418, 'message'=>'I\'m a teapot'],
            ['code'=>421, 'message'=>'Misdirected Request'],
            ['code'=>422, 'message'=>'Unprocessable Entity'],
            ['code'=>423, 'message'=>'Locked'],
            ['code'=>424, 'message'=>'Failed Dependency'],
            ['code'=>425, 'message'=>'Reserved for WebDAV advanced collections expired proposal'],
            ['code'=>426, 'message'=>'Upgrade Required'],
            ['code'=>428, 'message'=>'Precondition Required'],
            ['code'=>429, 'message'=>'Too Many Requests'],
            ['code'=>431, 'message'=>'Request Header Fields Too Large'],
            ['code'=>451, 'message'=> 'Unavailable For Legal Reasons'],
            ['code'=>500, 'message'=>'Internal Server Error'],
            ['code'=>501, 'message'=>'Not Implemented'],
            ['code'=>502, 'message'=>'Bad Gateway'],
            ['code'=>503, 'message'=>'Service Unavailable'],
            ['code'=>504, 'message'=>'Gateway Timeout'],
            ['code'=>505, 'message'=>'HTTP Version Not Supported'],
            ['code'=>506, 'message'=>'Variant Also Negotiates'], 
            ['code'=>507, 'message'=>'Insufficient Storage'],
            ['code'=>508, 'message'=>'Loop Detected'],
            ['code'=>510, 'message'=>'Not Extended'],
            ['code'=>511, 'message'=>'Network Authentication Required']
        ];

        foreach($responseArray as $response) {

            if($response['code'] == $responseCode) {
	        	$message = $response['message'];

	        	if($errorMsg) {
	        		$message = $errorMsg;
	        	}
	        	return $response = ['code'=>$responseCode,'message'=>$message];
            }
        }

	}
}

if(!function_exists('checkBPFocusModelStock')){
	function checkBPFocusModelStock($modelName)
	{
		$data = \DB::table('bp_model_stocks')
		->where('model_name','like','%'.$modelName.'%')
		->first();
		
		return $data;
	}
}

if(!function_exists('checkModelStock')) {
	function checkModelStock($modelName)
	{
		$data = \DB::table('product_masters')
		->where('product_model','like','%'.$modelName.'%')
		->first();
		
		return $data;
	}
}

if(!function_exists('checkModelStockByBP')){
	function checkModelStockByBP($groupId,$productMasterId,$modelName)
	{
		$data = \DB::table('bp_model_stocks')
		->where('bp_category_id','=',$groupId)
		->where('product_master_id','=',$productMasterId)
		->where('model_name','like','%'.$modelName.'%')
		->first();
		
		return $data;
	}
}




















