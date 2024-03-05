<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use Cache;
use Carbon\Carbon;
use DB;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $GetUser = DB::table('users')->paginate(10);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $GetUser = DB::table('users')
                ->where('id',$query)
                ->orWhere('name','like', '%'.$query.'%')
                ->orWhere('email','like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.user.result_data', compact('GetUser'))->render();
        }
        return view('admin.user.list',compact('GetUser'));
    }

    public function GetUserList(Request $request)
    {
    	//$GetUser =  GetTableWithPagination('users',10);
        $empList = Employee::where('status',1)->get(['id','name']);
    	$GetUser =  User::paginate(10);
    	if ($request->ajax()) {
            return view('admin.user.result_data', compact('GetUser','empList'));
        }
    	return view ('admin.user.list',compact('GetUser','empList'));
    }

    public function CreateUser(Request $request)
    {
    	//dd($request->all());
        $rules = [
            //'name'=>'required',
            'email'=>'required',
            'password'=>'required|confirmed|min:5',
            'password_confirmation'=>'required_with:password|same:password|min:5'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return response()->json([
            'fail'=>true,
            'errors'=>$validator->errors()
        ]);

    	$name        = $request->input('name');
        $email       = $request->input('email');
        $password    = $request->input('password');
        $empId       = $request->input('employee_id');

        $CheckUser   = User::where('email',$email)->first();

        if($CheckUser)
        {
            $UpdateUser = User::where('id',$CheckUser['id'])
            ->update([
                "employee_id"=>$empId ? $empId:$CheckUser['employee_id'],
                "name"=>$name,
                "email"=>$email,
                "password"=>Hash::make($password),
                "updated_at"=>Carbon::now()
            ]);
            return response()->json('success');
        } 
        else 
        {
            $AddUser = User::create([
                "employee_id"=>0,
                "name"=>$name,
                "email"=>$email,
                "password"=>Hash::make($password),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            $id = DB::getPdo()->lastInsertId();

            $updateUser = User::where('id',$id)
            ->update([
                "employee_id"=>$id,
                "updated_at"=>Carbon::now()
            ]);

            return response()->json('success');
        }
    }

    public function edit($id)
    {
    	$empList   = Employee::where('status',1)->get(['id','name']);
        $ShowUser  = DB::table('users')->where('id',$id)->first();
    	//dd($ShowUser);
    	return response()->json($ShowUser);
    }

    public function update(Request $request,$update_id)
    {
        //dd($request->all());
        $rules = [
            'name'=>'required',
            'email'=>'required',
            //'password'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return response()->json([
            'fail'=>true,
            'errors'=>$validator->errors()
        ]);

    	$name        = $request->input('name');
        $email       = $request->input('email');
        $password    = $request->input('password');
        $oldPassword = $request->input('old_password');
        $employeeId  = $request->input('update_employee_id');

        $userExists  = DB::table('users')
        ->where('email',$email)
        //->where('id','<>',$update_id)
        ->whereNotIn('id',[$update_id])
        ->first();

        $updatePassword = "";
        if($password) {
            $updatePassword = Hash::make($password);
        }

        if (!$userExists) {
        	$UpdateUser = DB::table('users')->where('id',$update_id)
	        ->update([
                "employee_id"=>$employeeId ? $employeeId:0,
	            "name"=>$name,
	            "email"=>$email,
	            "password"=>$updatePassword ? $updatePassword:$oldPassword
	        ]);
	        return response()->json('user-success');
        } else{
        	return response()->json('error');
        }
    }


    public function show()
    {
        $UserStatus = User::all();
        return view('admin.status', compact('UserStatus'));
    }
    
    public function getUserProfile($id)
    {
        $UserProfileInfo   = DB::table('users')->where('id',$id)->first();
        return view('admin/user/profile',compact('UserProfileInfo'));
    }

    public function userProfileUpdate(Request $request)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required',
            //'password'=>'required|confirmed|min:5',
            //'password_confirmation'=>'required_with:password|same:password|min:5'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return response()->json([
            'fail'=>true,
            'errors'=>$validator->errors()
        ]);

        $name        = $request->input('name');
        $email       = $request->input('email');
        $password    = $request->input('password');


        $CheckUser   = User::where('email',$email)->first();

        if($CheckUser)
        {
            if(isset($password) && !empty($password)) {
                $UpdateUser = User::where('id',$CheckUser['id'])
                ->update([
                    "name"=>$name,
                    "email"=>$email,
                    "password"=>Hash::make($password)
                ]);
            } else {
                $UpdateUser = User::where('id',$CheckUser['id'])
                ->update([
                    "name"=>$name,
                    "email"=>$email
                ]);
            }

            if($UpdateUser) {
                return redirect()->back()->with('success','Profile Update Successfully');
            } else {
                return redirect()->back()->with('error','Profile Update Failed.Please Try Again');
            }
        }
        return redirect()->back()->with('error','Profile Update Failed.Please Try Again');
    }
    
    public function getUserLog(Request $request)
    {
        $loginLogList = DB::table('login_activities as lc')
        ->select('lc.type','lc.created_at','lc.user_agent','lc.ip_address','users.name as name')
        ->leftJoin('users','users.id', '=', 'lc.user_id')
        ->orderBy('lc.id','desc')
        ->paginate(50);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

                $loginLogList = DB::table('login_activities as lc')
                ->select('lc.type','lc.created_at','lc.user_agent','lc.ip_address','users.name as name')
                ->leftJoin('users','users.id', '=', 'lc.user_id')
                ->where('lc.id',$query)
                ->orWhere('lc.type','like', '%'.$query.'%')
                ->orWhere('users.name', 'like', '%'.$query.'%')
                ->orWhere('lc.ip_address', 'like', '%'.$query.'%')
                ->orWhere('lc.created_at', 'like', '%'.$query.'%')
                ->orderBy('lc.id','desc')
                ->paginate(50);
            return view('admin.log.result_data', compact('loginLogList'))->render();
        }
        return view('admin.log.list',compact('loginLogList'));
    }
}