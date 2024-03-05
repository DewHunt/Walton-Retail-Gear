<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table="zones";

    protected $fillable = ['zone_id','zone_name'];

    public $timestamps = false;
}
