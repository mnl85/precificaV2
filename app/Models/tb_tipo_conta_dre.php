<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_tipo_conta_dre extends Model
{
    use HasFactory;
    protected $fillable = ['nome_ref','nome','alt_user_id','deleted'];
    protected $table = 'tb_tipo_conta_dre';
}
