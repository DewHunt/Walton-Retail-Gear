<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = "product_masters";

    protected $fillable = ["product_id","product_code","product_type","product_model","category2"];

    //public $timestamps = false;
}
