<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rsm extends Model
{
    use HasFactory;

    protected $table = "rsms";
    protected $fillable = ["rsm","rsm_id","asm","asm_id","tso","tso_id","email_address","mobile_no","zone","distributor_name","district","code","import_code"];


    //protected $hidden = [`updated_at`, `created_at`];

    public function distributor()
    {
    	return $this->hasOne(Distributor::class);
    }
}
