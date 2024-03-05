<?php
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
if(!function_exists('getApiTableWhere')) {
 
    function getApiTableWhere($table,$where) {
        $data = \DB::table($table)
            ->select(\DB::raw('*'))
            ->where($where)
            ->first();
            //->pluck('name', 'employee_id','email');
        return $data;
    }
}
