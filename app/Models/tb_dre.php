<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_dre extends Model
{
    use HasFactory;
    protected $fillable = ['ano','mes','empresa_id','entrada','folha','materiais','funcionarios_qtd','hora_mes_func','custos_fixos','alt_user_id','deleted'];
    protected $table = 'tb_dre';
}
