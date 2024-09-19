<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_empresa extends Model
{
    use HasFactory;
    protected $fillable = ['nome_empresa','id_user_responsavel','imposto_padrao'];
    protected $table = 'tb_empresa';
}
