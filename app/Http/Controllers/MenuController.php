<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Menu;
use DB,Validator,Carbon;


class MenuController extends Controller
{
    public function index(Request $request)
    {
        $nullMenus = Menu::select('menus.*','parent_menu as parentName')
            ->whereNull('parent_menu');

        $menuList = DB::table('menus as tab1')
            ->select('tab1.*','tab2.menu_name as parentName')
            ->join('menus as tab2','tab2.id','=','tab1.parent_menu')
            ->union($nullMenus)
            ->orderBy('id','asc')
            ->paginate(1000);

        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $menuList = DB::table('menus as tab1')
                ->select('tab1.*','tab2.menu_name as parentName')
                ->join('menus as tab2','tab2.id','=','tab1.parent_menu')
                ->union($nullMenus)
                ->where('tab1.status',1)
                ->orWhere('tab1.menu_name','like', '%'.$query.'%')
                ->orWhere('tab1.parent_menu','=',$query)
                ->orWhere('tab1.menu_link','like', '%'.$query.'%')
                ->orWhere('tab1.menu_icon','like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(1000);
            return view('admin.menu.result_data')->with(compact('menuList'))->render();
        }
        Log::info('Load User Roles Menu List');
        return view('admin.menu.list')->with(compact('menuList'));
    }

    public function add()
    {
        $title = "Add New Menu";
        $formLink = "menu.save";
        $buttonName = "Save";

        $menuList = Menu::orderBy('menu_name','asc')->get();
        $menuOrderBy = Menu::whereNull('parent_menu')->max('order_by');

        if (@menuOrderBy) {
            $orderBy = $menuOrderBy + 1;
        } else {
            $orderBy = 1;
        }       

        return view('admin.menu.add')->with(compact('title','formLink','buttonName','menuList','orderBy'));
    }

    public function save(Request $request)
    {
        //dd($request->all());
        $status = false;        
        $checkMenuLink = Menu::where('menu_link',$request->menuLink)->first();

        if ($checkMenuLink && $request->menuLink != "") {
            $status = false;
        } else {
            Menu::create([
                'parent_menu' => $request->parentMenuId,
                'menu_name' => $request->menuName,
                'menu_link' => $request->menuLink,
                'menu_icon' => $request->menuIcon ? $request->menuIcon:'fa fa-user',
                'order_by' => $request->orderBy,
                'created_at' => date('Y-m-d'),
            ]);

            $status = true;
        }

        if($status == true) {
            return response()->json('success');
        }
        return response()->json('error');
    }

    public function edit($menuId)
    {
        $formLink   = "menu.update";
        $buttonName = "Update";

        $menuList = Menu::orderBy('menu_name','asc')->get();
        $menuInfo = Menu::where('id',$menuId)->first();

        if(!empty($menuInfo)){
            return response()->json($menuInfo);
        }
        return response()->json('error');
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $status = false;
        $checkMenuLink = Menu::where('menu_link',$request->menuLink)->where('id','<>',$request->update_id)->first();

        if ($checkMenuLink) {
            $status = false;
            return response()->json('error');
        } else {
            $menuInfo = Menu::find($request->update_id);
            $menuInfo->update([
                'parent_menu' => $request->parentMenuId,
                'menu_name' => $request->menuName,
                'menu_link' => $request->menuLink,
                'menu_icon' => $request->menuIcon,
                'order_by' => $request->orderBy,
                'status'=> $request->status,
                'updated_at' => date('Y-m-d'),
            ]);
            $status = true;
        }

        if($status == true) {
            return response()->json('success');
        }
        return response()->json('error'); 
    }

    public function view($menuId)
    {
        $title = "Menu Information";

        $menuInfo = DB::table('menus as tab1')
            ->select('tab1.*','tab2.menu_name as parentName')
            ->join('menus as tab2','tab2.id','=','tab1.parent_menu')
            ->first();

        $menuActionList = MenuAction::select('menu_actions.*','menu_action_types.name as menuTypeName')
            ->leftJoin('menu_action_types','menu_action_types.id','menu_actions.menu_type_id')
            ->where('menu_actions.parent_menu_id',$menuId)
            ->orderBy('menu_actions.order_by','asc')
            ->get();

        return view('admin.menu.view')->with(compact('title','menuInfo','menuActionList'));
    }

    public function delete(Request $request)
    {
        Menu::where('id',$request->id)->delete();
        MenuAction::where('parent_menu_id',$request->id)->delete();
    }

    public function status(Request $request)
    {
        $menu = Menu::find($request->id);

        if ($menu->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $menu->update( [               
            'status' => 1                
        ]);
    }

    public function getMaxOrderBy(Request $request)
    {
        if ($request->parentMenuId) {
            $menuOrderBy = Menu::where('parent_menu',$request->parentMenuId)->max('order_by');
        } else {
            $menuOrderBy = Menu::whereNull('parent_menu')->max('order_by');
        }

        if (@$menuOrderBy) {
            $orderBy = $menuOrderBy+1;
        } else {
            $orderBy = 1;
        }
        
        if($request->ajax()) {
            return response()->json([
                'orderBy'=>$orderBy
            ]);
        }
    }

    public function changeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = menu::find($id);
            $old_status = $StatusInfo->status;


            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateMenuStatus = menu::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateMenuStatus) {
                Log::info('Mneu Status Updated Successfully');
                return response()->json('success');
            } else {
                Log::error('Mneu Status Updated Failed');
                return response()->json('error');
            }
        } else {
            Log::warning('Mneu id is Missing When Status Changed');
            return response()->json('error');
        } 
    }
}