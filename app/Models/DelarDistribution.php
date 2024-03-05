<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelarDistribution extends Model
{
    use HasFactory;

    protected $connection = "mysql2";
    protected $table = "dealer_distributions";

    protected $fillable = ["barcode","barcode2","dealer_code","distribution_date","product_master_id","color_id"];

    protected $hidden = [];
}
