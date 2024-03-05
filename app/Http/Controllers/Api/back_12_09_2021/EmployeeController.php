<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Validator;
use Mail;
use Response;

class EmployeeController extends Controller
{
    
    public function index(Request $request)
    {
        $EmployeeList = GetTableWithPagination('view_employee_list',100);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $EmployeeList = DB::table('view_employee_list')
                ->where('id',$query)
                ->orWhere('name','like', '%'.$query.'%')
                ->orWhere('designation', 'like', '%'.$query.'%')
                ->orWhere('department', 'like', '%'.$query.'%')
                ->orWhere('mobile_number', 'like', '%'.$query.'%')
                ->orWhere('status',$query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.employee.result_data', compact('EmployeeList'))->render();
        }

        if(isset($EmployeeList) && $EmployeeList->isNotEmpty()) {
            Log::info('Load Employee List');
        } else {
            Log::warning('Employee List Not Found');
        }
        return view('admin.employee.list',compact('EmployeeList'));
    }

    public function create($key)
    {
        $checkStatus = User::where('activation_key',$key)->first();

        if(isset($checkStatus) && !empty($checkStatus)) {
            return view('admin.employee.update-password',compact('key'));
        } else {
            return redirect('/home');
        }
    }
    
    public function store(Request $request)
    {        
        $rules = [
            //'employee_id'=>'required',
            'name'=>'required',
            //'designation'=>'required',
            //'education'=>'required',
            //'responsibility'=>'required',
            //'joining_date'=>'required',
            'mobile_number'=>'required|digits:11|numeric',
            'email'=>'required',
            //'status'=>'required',
            //'operating_unit'=>'required',
            //'product'=>'required',
            //'department'=>'required',
            //'section'=>'required',
            //'sub_section'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Create Employee Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }


        $EmpID          = $request->input('employee_id');
        $EmpPhone       = $request->input('mobile_number');
        $get_join_date  = $request->input('joining_date');
        //$joining_date   = Carbon::createFromFormat('m/d/Y', $get_join_date)->format('d/m/Y');

        $CheckEmp           = employee::where('mobile_number',$EmpPhone)->first();
        
        if($CheckEmp)
        {
            $CheckUserTable     = User::where('employee_id',$CheckEmp['id'])->first();
            $UpdateEmployee = employee::where('employee_id',$CheckEmp['id'])
            ->update([
                "employee_id"=>$request->input('employee_id'),
                "name"=>$request->input('name'),
                "designation"=> $request->input('designation'),
                "education"=> $request->input('education'),
                "responsibility"=> $request->input('responsibility'),
                "joining_date"=> "",
                "mobile_number"=> $request->input('mobile_number'),
                "email"=> $request->input('email'),
                "operating_unit"=> $request->input('operating_unit'),
                "product"=> $request->input('product'),
                "department"=> $request->input('department'),
                "section"=> $request->input('section'),
                "sub_section"=> $request->input('sub_section'),
                "status"=>$request->input('status')
            ]);

            
            if(isset($CheckUserTable) && !empty($CheckUserTable)){
                $UpdateEmpByUser = User::where('employee_id',$CheckEmp['id'])
                ->update([
                    "name"=>$request->input('name'),
                    "email"=>$request->input('email'),
                    "status"=>$request->input('status'),
                    "updated_at"=>Carbon::now()
                ]);
            }
            Log::info('Existing Employee Update');
            return response()->json('success');
        } 
        else 
        {
            $AddEmployee = employee::create([
                "employee_id"=>$request->input('employee_id'),
                "name"=>$request->input('name'),
                "designation"=> $request->input('designation'),
                "education"=> $request->input('education'),
                "responsibility"=> $request->input('responsibility'),
                "joining_date"=> "",
                "mobile_number"=> $request->input('mobile_number'),
                "email"=> $request->input('email'),
                "operating_unit"=> $request->input('operating_unit'),
                "product"=> $request->input('product') ? $request->input('product'):'Mobile',
                "department"=> $request->input('department') ? $request->input('department'):'HRM',
                "section"=> $request->input('section') ? $request->input('section'):'Corporate HRM',
                "sub_section"=> $request->input('sub_section'),
                "status"=>$request->input('status')
            ]);

            //Employee Password Generate For Login Access Start 
            $unique_number      = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
            $current_password   = substr(str_shuffle($unique_number), 0, 15);
            $activation_key     = $current_password;
            $current_date       = date('dmY'); 
            //Employee Password Generate For Login Access End

            $empId = DB::getPdo()->lastInsertId();
            $AddUser = User::create([
                "name"=>$request->input('name'),
                "employee_id"=>$empId,
                "email"=>$request->input('email'),
                "password"=>Hash::make('1234@@gs'),
                "password_confirmation"=>Hash::make('1234@@gs'),
                "activation_key"=>$activation_key,
                "status"=>$request->input('status'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            $ProjectUrl     = URL::to("/");
            $activation_url = $ProjectUrl."/employee-activation/".$activation_key; 

            $data = [
                'employee_id'=>$empId,
                'name'=>$request->input('name'),
                'subject' => "Register User Notification",
                'email' => $request->input('email'),
                'content' => "Please Click at  Activation Url For Confirmation Message...",
                "url"=>$activation_url
            ];

            Mail::send('admin.mail_confirmation.registration_mail', $data, function($message) use ($data) {
                $message->to($data['email']);
                $message->subject($data['subject']);
                $message->from('demoadmin@manush.co.uk','Retail Gear');
            });
            //dd('Mail Send Successfully');

            //return response()->json(['success'=>"Employee Insert Successfully",'password'=>$Employee_Password]);
            Log::info('Create Employee Success');
            return response()->json('success');
        }
        Log::error('Create Employee Failed');
        return response()->json('error');
    }

    public function api_store(Request $request)
    {        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://mapi.waltonbd.com/OutSourceApi/api/DealerDistribution?startDate=2021-01-01&endDate=2021-01-31",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = json_decode(curl_exec($curl),true);
        $err = curl_error($curl);

        if ($err) {
            return response()->json(['error'=>$err]);
        } else {

            $Status = 0;

            foreach ($response as $row) 
            {
                
                $EmpID = $row['EmployeeID'];

                $CheckEmp = employee::where('employee_id',$EmpID)->first();
                if($CheckEmp) 
                {
                    $UpdateEmployee = employee::where('employee_id',$EmpID)
                    ->update([
                        "name"=>$row['name'],
                        "designation"=> $row['designation'],
                        "product_model"=> $row['designation'],
                        "education"=> $row['education'],
                        "responsibility"=> $row['responsibility'],
                        "joining_date"=> $row['joining_date'],
                        "mobile_number"=> $row['mobile_number'],
                        "email"=> $row['email'],
                        "operating_unit"=> $row['operating_unit'],
                        "product"=> $row['product'],
                        "department"=> $row['department'],
                        "section"=> $row['section'],
                        "sub_section"=> $row['sub_section'],
                        "photo"=> $row['photo']
                    ]);

                } 
                else 
                {

                    $AddEmployee = employee::create([
                        "name"=>$row['name'],
                        "employee_id"=>$row['employee_id'],
                        "designation"=> $row['designation'],
                        "product_model"=> $row['designation'],
                        "education"=> $row['education'],
                        "responsibility"=> $row['responsibility'],
                        "joining_date"=> $row['joining_date'],
                        "mobile_number"=> $row['mobile_number'],
                        "email"=> $row['email'],
                        "operating_unit"=> $row['operating_unit'],
                        "product"=> $row['product'],
                        "department"=> $row['department'],
                        "section"=> $row['section'],
                        "sub_section"=> $row['sub_section'],
                        "photo"=> $row['photo']
                    ]);

                }

                $status = 1;

            }

            if($Status == 1) {

                return response()->json(["success"=>"Employee Insert Successfully"]);

            } else {

                return response()->json(["error"=>"Data All Ready Taken...."]);
            }

        }

        curl_close($curl);
    }

    public function show(Employee $employee)
    {
        //
    }

    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $EmployeeInfo = DB::table('view_employee_list')
            ->where('id',$id)
            ->first();

            if($EmployeeInfo) {
                Log::info('Get Employee By Id');
                return response()->json($EmployeeInfo);
            } else {
                Log::info('Employee Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::info('Invalid Employee Id');
            return response()->json('error');
        }
    }

    public function update(Request $request,$update_id)
    {
        $rules = [
            //'employee_id'=>'required',
            'name'=>'required',
            //'designation'=>'required',
            //'education'=>'required',
            //'responsibility'=>'required',
            //'joining_date'=>'required',
            'mobile_number'=>'required|digits:11|numeric',
            //'email'=>'required',
            //'status'=>'required',
            //'operating_unit'=>'required',
            //'product'=>'required',
            //'department'=>'required',
            //'section'=>'required',
            //'sub_section'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Employee Update Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $EmpUpdateID        = $request->input('update_id');
        $EmpPhone           = $request->input('mobile_number');
        $employee_id        = $request->input('employee_id');
        $get_join_date      =  $request->input('joining_date');
        $CheckEmp           = employee::where('id',$EmpUpdateID)->first();
        $CheckUserTable     = User::where('employee_id',$CheckEmp['id'])->first();
        
        if($CheckEmp)
        {
            $UpdateEmployee = employee::where('id',$EmpUpdateID)
            ->update([
                "employee_id"=>$request->input('employee_id'),
                "name"=>$request->input('name'),
                "designation"=> $request->input('designation'),
                "education"=> $request->input('education'),
                "responsibility"=> $request->input('responsibility'),
                "joining_date"=> "",
                "mobile_number"=> $request->input('mobile_number'),
                "email"=> $request->input('email'),
                "operating_unit"=> $request->input('operating_unit'),
                "product"=> $request->input('product'),
                "department"=> $request->input('department'),
                "section"=> $request->input('section'),
                "sub_section"=> $request->input('sub_section'),
                "status"=>$request->input('status')
            ]);

            if(isset($CheckUserTable) && !empty($CheckUserTable)){
                $UpdateEmpByUser = User::where('employee_id',$CheckEmp['id'])
                ->update([
                    "name"=>$request->input('name'),
                    "email"=>$request->input('email'),
                    "status"=>$request->input('status'),
                    "updated_at"=>Carbon::now()
                ]);
            }

            Log::info('Existing Employee Updated Successfully');
            return response()->json('success');
        }
        Log::error('Existing Employee Not Found'); 
        return response()->json('error');       
    }

    public function CheckEmployee($id)
    {
        $getCurlResponse    = getData(sprintf(RequestApiUrl("EmployeeId"),$id),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            if($responseData == "NO Data Found"){
                return response()->json(['error'=>"NO Data Found"]);
            }
            else {
                return response()->json(['success'=>$responseData]);
            }
            
        } else {
            return response()->json($getCurlResponse['response_data']);
            //return response()->json(["error"=>$getCurlResponse['response_data']]);
        }
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = employee::find($id);
            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $UpdateEmployeeStatus = employee::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateEmployeeStatus) {
                Log::info('Employee Status Changed Successfully');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Employee Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::error('Employee Status Changed Failed');
            return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }

    public function account_update(Request $request)
    {
        $rules = [
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        return redirect()->back()->with('errors',$validator->errors());

        $password       = $request->input('password');
        $activation_key = $request->input('activation_key');

        $checkStatus = User::where('activation_key',$activation_key)->first();

        if(isset($checkStatus) && !empty($checkStatus))
        {
            $UpdateEmployee = User::where('id',$checkStatus['id'])
            ->update([
                "name"=>$checkStatus['name'],
                "email"=>$checkStatus['email'],
                "password"=>Hash::make($password),
                "activation_key"=>"",
                "updated_at"=> Carbon::now()
            ]);
            Log::info('Employee Account Activated Successfully');
            return redirect('/home')->with('success','You are Successfully Activated Your Account');
        }
        else{
            Log::error('Employee Account Activated Failed');
            return view('admin.employee.update-password')->with('error');
        }
    }
    
    public function destroy(Employee $employee)
    {
        //
    }

    public function getEmployeeInfo($id)
    {
        if(isset($id) && $id > 0) {
            $EmployeeInfo = DB::table('view_employee_list')
            ->select('id','name','email','employee_id')
            ->where('id',$id)
            ->first();

            if($EmployeeInfo) {
                Log::info('Get Employee By Id');
                return response()->json($EmployeeInfo);
            } else {
                Log::info('Employee Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::info('Invalid Employee Id');
            return response()->json('error');
        }
    }

}
