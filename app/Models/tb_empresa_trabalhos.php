<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_empresa_trabalhos extends Model
{
    use HasFactory;
    protected $fillable = ['empresa_id','trabalho_id','valor_cobrado','alt_user_id','deleted'];

}
