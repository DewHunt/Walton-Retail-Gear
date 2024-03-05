<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Employee;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Menu;
use Cache;
use Carbon\Carbon;
use DB;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $GetUser =  DB::table('users')->paginate(100); //GetTableWithPagination('users',10);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $GetUser = DB::table('users')
                ->where('id',$query)
                ->orWhere('name','like', '%'.$query.'%')
                ->orWhere('employee_id','like', '%'.$query.'%')
                ->orWhere('email','like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.user.result_data', compact('GetUser'))->render();
        }
        return view('admin.user.list',compact('GetUser'));
    }

    public function GetUserList(Request $request)
    {
        $empList = Employee::where('status',1)->get(['id','name']);
    	$GetUser = User::paginate(100);
    	if ($request->ajax()) {
            return view('admin.user.result_data', compact('GetUser','empList'));
        }
    	return view ('admin.user.list',compact('GetUser','empList'));
    }

    public function CreateUser(Request $request)
    {
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
        $status      = $request->input('status');

        $CheckUser   = User::where('email',$email)->first();

        if($CheckUser)
        {
            $UpdateUser = User::where('id',$CheckUser['id'])
            ->update([
                "employee_id"=>$empId ? $empId:$CheckUser['employee_id'],
                "name"=>$name,
                "email"=>$email,
                "password"=>Hash::make($password),
                "status"=>$status,
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
                "status"=>$status,
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

    	return response()->json($ShowUser);
    }

    public function update(Request $request,$update_id)
    {
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
        $status      = $request->input('status');

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
	            "password"=>$updatePassword ? $updatePassword:$oldPassword,
                "status"=>$status,
                "updated_at"=>Carbon::now()
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
        $name        = $request->input('name');
        $email       = $request->input('email');
        $password    = $request->input('password');

        $rules = [
            'name'=>'required',
            'email'=>'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return redirect()->back()->with('errors',$validator->errors());

        if(isset($password) && !empty($password)) {
            $rules = [
                'password'=>'required|confirmed|min:5',
                'password_confirmation'=>'required_with:password|same:password|min:5'
            ];
            $validator = Validator::make($request->all(), $rules);
        
            if($validator->fails())
            return redirect()->back()->with('errors',$validator->errors());
        }

        $CheckUser   = User::where('email',$email)->first();

        if($CheckUser)
        {
            $status = 0;
            if(isset($password) && !empty($password)) {
                $UpdateUser = User::where('id',$CheckUser['id'])
                ->update([
                    "name"=>$name,
                    "email"=>$email,
                    "password"=>Hash::make($password),
                    "updated_at"=>Carbon::now()
                ]);
                $status = 1;
            } else {
                $UpdateUser = User::where('id',$CheckUser['id'])
                ->update([
                    "name"=>$name,
                    "email"=>$email,
                    "updated_at"=>Carbon::now()
                ]);

                $status = 1;
            }

            if($status == 1) {
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

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = User::find($id);
            $empId      = $StatusInfo->employee_id;
            $bpId       = $StatusInfo->bp_id;
            $retailerId = $StatusInfo->retailer_id;

            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $UpdateUserStatus = User::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateUserStatus) {

                if(isset($empId) && $empId > 0) {
                    Employee::where('id',$empId)
                    ->update([
                        "status"=> $UpdateStatus ? $UpdateStatus:0
                    ]);
                }

                if(isset($bpId) && $bpId > 0) {
                    BrandPromoter::where('id',$bpId)
                    ->update([
                        "status"=> $UpdateStatus ? $UpdateStatus:0
                    ]);
                }

                if(isset($retailerId) && $retailerId > 0) {
                    Retailer::where('id',$retailerId)
                    ->update([
                        "status"=> $UpdateStatus ? $UpdateStatus:0
                    ]);
                }

                Log::info('User Status Changed Successfully');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('User Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::error('User Status Changed Failed');
            return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }

    public function menuPermission($userId)
    {
        $parentMenus    = Menu::where('status',1)
        ->whereNull('parent_menu')
        ->orderBy('id','ASC')
        ->get(['id','parent_menu','menu_name','menu_link']);

        $childMenus = Menu::where('status',1)
        ->whereNotNull('parent_menu')
        ->orderBy('id','ASC')
        ->get(['id','parent_menu','menu_name','menu_link']);

        $userInfo       = User::where('id',$userId)->first();
        //$userRole   = UserRole::where('id',$userInfo->user_role_id)->first();

        //$getPermissionMenuId = explode(",",$userInfo['permission_menu_id']));
        //print_r($getPermissionMenuId);

        return view('admin.user.permission')->with(compact('parentMenus','childMenus','userInfo'));
    }

    public function userMenuPermissionSave(Request $request)
    {
        //dd($request->all());
        $userId = $request->input('user_id');
        $menuId = implode(',',$request->input('permission_menu_id'));
        $status = User::where('id','=',$userId)
        ->update([
            "permission_menu_id"=>$menuId,
            "updated_at"=>Carbon::now()
        ]);

        if($status) {
            return redirect()->back()->with('success','User Permission Assigned Successfully');
        }
        return redirect()->back()->with('error','User Permission Assigned Failed');
    }
}