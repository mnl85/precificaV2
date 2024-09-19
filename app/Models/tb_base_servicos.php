<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_base_servicos extends Model
{
    use HasFactory;
    protected $fillable = ['servico','tempo','deleted','alt_user_id'];
}
