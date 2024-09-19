<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log_manual_restarts extends Model
{
    use HasFactory;
    protected $fillable = ['alt_user_id'];
}
