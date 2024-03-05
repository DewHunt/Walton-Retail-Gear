<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\User;
use App\Models\ApiLoginActivity;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class JwtAuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','signout']]);
        $this->guard = "api";
    }

    /**
     * Get a JWT via given credentials.
    */
    public function login(Request $request)
    {
        //return $request->all();exit();
        $login_phone_number = $request->input('phone_number'); //"01302046931";
    	$req = Validator::make($request->all(), [
            'phone_number' => 'required|numeric|digits:11',
            'password' => 'required|string|min:5',
        ]);

        if ($req->fails()) {
            return response()->json($req->errors(), 422);
        }

        $CheckUserList = ViewTableListWhere('view_check_login_user',$login_phone_number);
    
        if(isset($CheckUserList) && !empty($CheckUserList))
        {
            $loginPassword = $request->input('password');
            if($CheckUserList->bp_id > 0 && $CheckUserList->brand_promoter_phone == $login_phone_number)
            {
                $dataArray = [
                    'bp_id'=>$CheckUserList->bp_id,
                    'password'=>$loginPassword
                ];
                if (! $token = auth($this->guard)->attempt($dataArray)) {
                    $errorMsg = 'Phone Number Or Password Invalid';
                    return response()->json(apiResponses(203,$errorMsg),203);
                }
                
                ApiLoginActivity::create([
                    "type" => "BP Successfully Loged In",
                    "user_agent" => $request->server('HTTP_USER_AGENT'),
                    "user_id" => $CheckUserList->id,
                    "ip_address" => $request->ip(),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
                return $this->generateToken($token);
            }
            elseif($CheckUserList->retailer_id > 0 && $CheckUserList->retailer_phone == $login_phone_number) {
                $dataArray = [
                    'retailer_id'=>$CheckUserList->retailer_id,
                    'password'=>$loginPassword
                ];
                if (! $token = auth($this->guard)->attempt($dataArray)) {
                    $errorMsg = 'Phone Number Or Password Invalid';
                    return response()->json(apiResponses(203,$errorMsg),203);
                }
                
                ApiLoginActivity::create([
                    "type" => "Retailer Successfully Loged In",
                    "user_agent" => $request->server('HTTP_USER_AGENT'),
                    "user_id" => $CheckUserList->id,
                    "ip_address" => $request->ip(),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
                return $this->generateToken($token);
            }
            else {
                
                $dataArray = [
                    'employee_id'=>$CheckUserList->employee_id,
                    'password'=>$loginPassword
                ];
                if (! $token = auth($this->guard)->attempt($dataArray)) {
                    $errorMsg = 'Employee Id Or Password Invalid';
                    return response()->json(apiResponses(203,$errorMsg),203);
                }
                
                ApiLoginActivity::create([
                    "type" => "Employee Successfully Loged In",
                    "user_agent" => $request->server('HTTP_USER_AGENT'),
                    "user_id" => $CheckUserList->id,
                    "ip_address" => $request->ip(),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
                return $this->generateToken($token);
            }
        }
        else 
        {
            Log::error('Apps Login Error');
            return response()->json(apiResponses(401),401);
        }

           
    }

    /**
     * Sign up.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $req = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($req->fails()){
            return response()->json($req->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $req->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User signed up',
            'user' => $user
        ], 201);
    }


    /**
     * Sign out
    */
    public function signout(Request $request) {
        $userId     = auth('api')->user()->id;
        ApiLoginActivity::create([
            "type" => "Logged Out",
            "user_agent" => $request->server('HTTP_USER_AGENT'),
            "user_id" => $userId ? $userId:1,
            "ip_address" => $request->ip(),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);
        auth($this->guard)->logout();
        return response()->json(['message' => 'User loged out','code'=>200],200);
    }

    /**
     * Token refresh
    */
    public function refresh() {
        return $this->generateToken(auth($this->guard)->refresh());
    }

    /**
     * User
    */
    public function user() {
        return response()->json(auth($this->guard)->user());
    }

    /**
     * Generate token
    */
    protected function generateToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60,
            'user' => auth($this->guard)->user()
        ]);
    }

}