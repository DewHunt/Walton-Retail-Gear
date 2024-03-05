<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialAward extends Model
{
    use HasFactory;

    protected $table 	= "special_awards";
    protected $guarded 	= [];
    public $timestamps 	= false;
}
