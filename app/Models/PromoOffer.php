<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoOffer extends Model
{
    use HasFactory;

    protected $table = "promo_offers";
    protected $guarded = [];
    public $timestamps = false;
}
