<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreBookin extends Model
{
    use HasFactory;

    protected $table    = "prebookings";
    protected $guarded  = [];
    public $timestamps  = false;
}
