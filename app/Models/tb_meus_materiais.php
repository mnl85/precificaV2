<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_meus_materiais extends Model
{
    use HasFactory;
    protected $fillable = ['material','apresentacao','custo','qtd_calculada','alt_user_id'];
}
