<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLoginActivity extends Model
{
    use HasFactory;

    public $table = "login_activities";
    protected $guarded = [];
}
