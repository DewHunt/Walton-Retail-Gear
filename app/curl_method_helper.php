<?php
if(!function_exists('methodName')) {
    
    function curlAPI($url,$type=null){
        $headers = array(
            // Set Here Your Requesred Headers
            'Content-Type: application/json',
            'AppApiKey:18197:mostafiz',
            'Content-Length: 0',
        );
        $process = curl_init($url); //your API url
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 20);
        curl_setopt($process, CURLOPT_POST, 1);
         curl_setopt($process, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($process);
        
            //    print_r($response);
        
       // $response = json_decode($response, true);
        
        
        return $response;
        
       // print_r($response);
      /*  if(isset($response) && !empty($response))
        {
        	//return $response;
        	$ReturnArray = ['status'=>'200','response_data'=>$response,'response_url'=>$url];
        	return $ReturnArray;
        }
        else
        {
        	$err = curl_error($curl);
        	//return $err
        	$ReturnArray = ['status'=>'400','response_data'=>$err,'response_url'=>$url];
        	
        	return $ReturnArray;
        }
        
        */
        curl_close($process);
        //print_r($return);
    }


    function getData($url,$type=null) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_IPRESOLVE=>CURL_IPRESOLVE_V4,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                'AppApiKey:18197:mostafiz',
            ),
        ));
        $response = "";
        //$response = json_decode(curl_exec($curl),true);
        $response = curl_exec($curl);

        if(isset($response) && !empty($response)) {
        	//return $response;
        	$ReturnArray = ['status'=>'200','response_data'=>$response,'response_url'=>$url];
        	return $ReturnArray;
        } else {
        	$err = curl_error($curl);
        	$ReturnArray = ['status'=>'400','response_data'=>$err,'response_url'=>$url];
        	return $ReturnArray;
        }
        curl_close($curl);
    }
}