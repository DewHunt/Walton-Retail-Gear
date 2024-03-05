<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMasterPrice extends Model
{
    use HasFactory;

    protected $table = "product_master_prices";

    protected $fillable = ['product_id','mrp_price','msdp_price','msrp_price'];
}
