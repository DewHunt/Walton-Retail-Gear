<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use App\Models\DealerInformation;
use App\Repositories\HomeInterface;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $model;
    public function __construct(HomeInterface $homeRepo,DealerInformation $dealer_information,Zone $zone)
    {
    	$this->middleware('auth');
        $this->home     = $homeRepo;
        $this->model    = new Repository($dealer_information);
        //$this->model    = new Repository($zone);
    }
    
    public function index()
    {
    	//Log::info('After Successfully Login and Dashboard View');
        //return $this->home->getAll();
    	//return $this->model->all();
        return view('admin.home');
    }

    /*
    //Note: If you are calling any methods and the method not in repository then you can get just using ‘getModel’ just like below.
    public function index()
    {
        $posts = $this->dealer_information->getModel()->orderBy('id', 'desc')->get();
        return response()->json($posts);
    }
    */

    public function store(Request $request)
    {
       return $this->model->create($request->all());
    }

    public function show($id)
    {
       return $this->model->show($id);
    }

    public function update(Request $request, $id)
    {
       $this->model->update($request->all(), $id);
    }

    public function destroy($id)
    {
       return $this->model->delete($id);
    }
}
