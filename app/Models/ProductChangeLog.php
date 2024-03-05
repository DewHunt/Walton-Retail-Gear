<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductChangeLog extends Model
{
    use HasFactory;

    protected $table 	= "product_change_logs";
    protected $guarded 	= [];
    public $timestamps 	= false;
}
