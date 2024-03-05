<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\Banner;
use Carbon\Carbon;
use DB;
use Validator;
use Response;


class BannerController extends Controller
{
    
    public function index(Request $request)
    {   
        $bannerList    = DB::table('banners')
        ->orderBy('id','desc')
        ->get();

        if(isset($bannerList) && $bannerList->isNotEmpty()) {
            Log::info('Load Banner List');
        } else {
            Log::warning('Banner List Not Found');
        }

        return view('admin.banner.list',compact('bannerList'));
    }

    
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $rules = [
            'banner_pic'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512',
            'status'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Create Banner Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $BannerPic = "";
        if($request->hasFile('banner_pic')) {
            $getPhoto = $request->file('banner_pic');
            $filename = time().'.'.$getPhoto->getClientOriginalExtension();
            $destinationPath = public_path('/upload/banner/');
            $success = $getPhoto->move($destinationPath, $filename);
        
            $BannerPic = $filename;
        }

        $baseUrl = URL::to('');
        //$bannerFullPath = $baseUrl.'/public/upload/banner/'.$BannerPic;
        $bannerFullPath = 'public/upload/banner/'.$BannerPic;

        $addBanner = Banner::create([
            "banner_for"=>$request->input('banner_for'),
            "banner_pic"=>$BannerPic ? $BannerPic : 'no-image.png',
            "image_path"=>$bannerFullPath,
            "status"=>$request->input('status'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        if($addBanner){
            Log::info('Create Banner Success');
            return response()->json('success');
        } else {
            Log::error('Create Banner Failed');
            return response()->json('error');
        }
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $editBannerInfo = Banner::where('id',$id)->first();

            if($editBannerInfo) {
                Log::info('Get Banner By Id');
                return response()->json(['bannerInfo'=>$editBannerInfo]);
            } else {
                Log::warning('Banner Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Banner Id');
            return response()->json('error');
        }
    }

    
    public function update(Request $request)
    {
        $update_id = $request->input('update_id');
        $rules = [
            //'banner_pic'=>'required',
            'status'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Banner Update Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $bannerInfo       = Banner::where('id',$update_id)->first();

        $BannerPic = "";
        if($request->hasFile('banner_pic')) {
            $getPhoto = $request->file('banner_pic');
            $filename = time().'.'.$getPhoto->getClientOriginalExtension();
            $destinationPath = public_path('/upload/banner/');
            $success = $getPhoto->move($destinationPath, $filename);
        
            $BannerPic = $filename;
        } else {
            $BannerPic = $bannerInfo['banner_pic'];
        }

        $baseUrl = URL::to('');
        //$bannerFullPath = $baseUrl.'/public/upload/banner/'.$BannerPic;
        $bannerFullPath = 'public/upload/banner/'.$BannerPic;
        

        $Update = Banner::where('id',$update_id)
        ->update([
            "banner_for"=>$request->input('banner_for'),
            "banner_pic"=>$BannerPic ? $BannerPic : 'no-image.png',
            "image_path"=>$bannerFullPath,
            "status"=>$request->input('status'),
            "updated_at"=>Carbon::now()
        ]);

        if($Update) {
            Log::info('Existing Banner Update Success');
            return response()->json('success');
        }
        Log::error('Existing Banner Update Failed');
        return response()->json('error');
    }

    
    public function destroy($id)
    {
        $Success = Banner::find($id)->delete();
        if($Success){ 
            Log::info('Banner Remove Success'); 
            return redirect()->route('banner.index')
                        ->with('success','Deleted Successfully');
        }else{
            Log::info('Banner Remove Failed');
            return redirect()->route('banner.index')
                        ->with('error','Deleted Failed');
        }
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = Banner::find($id);
            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $Status = Banner::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($Status) {
                Log::info('Banner Status Changed Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Banner Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Banner Id');
            return response()->json('error');
        }
        
    }

}
