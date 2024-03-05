<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\AuthorityMessage;
use Carbon\Carbon;
use DB;
use Validator;
use Response;

class AuthorityMessageController extends Controller
{
    public function index(Request $request)
    {
        $last_msg_list = DB::table('authority_messages as tab1')
            ->select('tab1.*')
            ->leftJoin('authority_messages as tab2',function($join_query) {
                $join_query->on('tab2.reply_for','=','tab1.reply_for');
                $join_query->on('tab2.id','>','tab1.id');
            })
            ->whereNull('tab2.id')
            ->where('tab1.bnm','=',0)
            ->groupBy('tab1.who_reply','tab1.id')
            ->orderBy('tab1.id','desc');

        $MessageList = DB::table('authority_messages')
            ->select('*')
            ->where('bnm','=',2)
            ->union($last_msg_list)
            ->orderBy('id','desc')
            ->paginate(50);

        if($request->ajax()) {
            $MessageList = "";
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

            $last_msg_list = DB::table('authority_messages as tab1')
                ->select('tab1.*')
                ->leftJoin('authority_messages as tab2',function($join_query) {
                    $join_query->on('tab2.reply_for','=','tab1.reply_for');
                    $join_query->on('tab2.id','>','tab1.id');
                })
                ->whereNull('tab2.id')
                ->where('tab1.bnm','=',0)
                ->groupBy('tab1.who_reply','tab1.id')
                ->orderBy('tab1.id','desc');

            $MessageList = DB::table('authority_messages')
                ->select('*')
                ->where('bnm','=',2)
                ->union($last_msg_list)
                ->orderBy('id','desc')
                ->paginate(50);


            return view('admin.message.result_data', compact('MessageList'))->render();
        }

            
        return view('admin.message.list',compact('MessageList'));
    }

    
    public function create()
    {
        return view('admin.message.add');
    }

    
    public function store(Request $request)
    {        
        //dd($request->all());
        $rules = [
            'message'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return redirect()->back()->with('errors',$validator->errors());

        $bp_id          = $request->input('bp_id');
        $retailer_id    = $request->input('retailer_id');
        $CheckStatus    = AuthorityMessage::where('message',$request->input('message'))->first();

        if($CheckStatus) {
            $UpdateStatus = AuthorityMessage::where('id',$CheckStatus['id'])
            ->update([
                "bp_id"=>$request->input('bp_id'),
                "retailer_id"=>$request->input('retailer_id'),
                "message"=>$request->input('message'),
                "date_time"=>date('Y-m-d h:i:s'),
                "status"=>$CheckStatus['status'] ? $CheckStatus['status']:'send',
                'reply_for'=>$CheckStatus['reply_for'] ? $CheckStatus['reply_for']:'',
            ]);
            return redirect()->back()->with('success','Data Insert Successfully');
        } else {

            $AddMessage = AuthorityMessage::create([
                "bp_id"=>$request->input('bp_id'),
                "retailer_id"=>$request->input('retailer_id'),
                "message"=>$request->input('message'),
                "date_time"=>date('Y-m-d h:i:s'),
                "status"=>'send',
                'reply_for'=>'',
            ]);
            return redirect()->back()->with('success','Data Insert Successfully');
        }
        Log::error('Authority Message Inserted Failed');
        return redirect()->back()->with('error','Data Insert Failed');
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $id             = \Crypt::decrypt($id);
        $messageInfo    = DB::table('authority_messages')
        ->where('id',$id)
        ->first();

        $bpName         = DB::table('brand_promoters')
        ->where('id',$messageInfo->bp_id)
        ->value('bp_name');

        $retailerName = DB::table('retailers')
        ->where('id',$messageInfo->retailer_id)
        ->value('retailer_name');

        return view('admin.message.edit',compact('messageInfo','bpName','retailerName'));
    }

    
    public function update(Request $request,$id)
    {
        //dd($request->all());
        $id             = \Crypt::decrypt($id);
        $rules = [
            'message'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return Response::json(['errors' => $validator->errors()]);

        $CheckStatus    = AuthorityMessage::where('id',$id)->first();


        $UpdateStatus = AuthorityMessage::where('id',$id)
        ->update([
            "bp_id"=>$request->input('bp_id'),
            "retailer_id"=>$request->input('retailer_id'),
            "message"=>$request->input('message'),
            "date_time"=>date('Y-m-d h:i:s'),
            "status"=>$CheckStatus->status ? $CheckStatus->status:'send',
            'reply_for'=>$CheckStatus->reply_for ? $CheckStatus->reply_for:'',
        ]);

        if($UpdateStatus){
            return redirect()->back()->with('success','Data Update Successfully');
        } else {
            Log::error('Authority Message Updated Failed');
            return redirect()->back()->with('error','Data Update Failed');
        }

    }

    
    public function destroy(AuthorityMessage $authority_message,$id)
    {
        $Success = DB::table('authority_messages')->where('id',$id)->delete();
        if($Success) {
            return redirect()->back()->with('success','Delete Successfully');
        } else {
            Log::error('Authority Message Deleted Failed');
            return redirect()->back()->with('error','Delete Failed');
        }                
    }

}
