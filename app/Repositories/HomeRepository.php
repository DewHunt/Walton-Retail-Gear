<?php
namespace App\Repositories;

use App\Models\Zone;
use App\Repositories\HomeInterface;

class HomeRepository implements HomeInterface{

	public function getAll()
	{
		return Zone::get();
	}
}