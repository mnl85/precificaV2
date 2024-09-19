<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_base_trabalhos extends Model
{
    use HasFactory;
    protected $fillable = ['trabalho','materiais','servicos','alt_user_id','visivel','deleted'];
}
