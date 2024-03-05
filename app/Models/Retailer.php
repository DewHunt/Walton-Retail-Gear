<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;

    protected $table="retailers";

    protected $fillable = ['retailer_id','retailer_name','retailder_address','status',
'owner_name','phone_number','payment_number_type','payment_number','zone_id','division_id','division_name','distric_id','distric_name','police_station','thana_id','distributor_code','distributor_code2'];

    public $timestamps = false;
}
