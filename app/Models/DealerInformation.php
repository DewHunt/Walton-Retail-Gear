<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerInformation extends Model
{
    use HasFactory;

    protected $table = "dealer_informations";
    protected $fillable = ["dealer_id","dealer_code","alternate_code","dealer_name","dealer_address","zone","city","division","dealer_phone_number","dealer_type"];

    //protected $hidden = [`updated_at`, `created_at`];

    public $timestamps = false;

    public function distributor() {

    	return $this->belongsTo(Distributor::class);
    	
    }
}
