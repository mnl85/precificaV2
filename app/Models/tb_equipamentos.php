<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_equipamentos extends Model
{
    use HasFactory;
    protected $fillable = ['empresa_id','nome_equipamento','valor','vida_util_horas','alt_user_id','deleted'];
    protected $table = 'tb_equipamentos';
}
