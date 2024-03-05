<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Colors;
use DB;
use Validator;

class ColorController extends Controller
{
    
    public function index()
    {
        
        $color_list = Colors::get();
        echo "<pre>";print_r($color_list);echo "</pre>";die();
        return response()->json($color_list);

        //return view('admin.employee.add');

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }
 
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
