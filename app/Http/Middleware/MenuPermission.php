<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\User;
use Auth;

class MenuPermission
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = \Request::route()->getName();
        $userMenu = Menu::where('menu_link',$routeName)->first();

        $userStatus =  Auth::user()->status;

        if ($userStatus == 1) {
            $userRoles = Auth::user();
            $actionLinkPermission = explode(',', $userRoles->permission_menu_id);
        }
        //dd($routeName);

        if (!empty($userMenu)) {
            if ($userStatus == 0) {
                return $next($request);
            } elseif (in_array($userMenu->id, @$actionLinkPermission)) {
                return $next($request); 
            } else {
                return redirect('/home')->with('error','You have not permission to access this menu');
            }            
        } else {
            return $next($request);
        }
    }
}
