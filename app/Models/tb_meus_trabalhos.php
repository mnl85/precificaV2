<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_meus_trabalhos extends Model
{
    use HasFactory;
    protected $fillable = ['trabalho','materiais','servicos','deleted','alt_user_id','valor_cobrado','copiada','frete'];
}
