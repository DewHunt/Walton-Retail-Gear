<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $table = "distributors";
    protected $fillable = ["digitech_code","zone","import_code","mobile_no"];

    //protected $hidden = [`updated_at`, `created_at`];

    public function DealerInfo()
    {
    	return $this->hasOne(DealerInformation::class);
    }

    public function rms(){
    	return $this->bellongsTo(rsm::class);
    }
}
