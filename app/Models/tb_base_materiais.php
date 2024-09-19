<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_base_materiais extends Model
{
    use HasFactory;
    protected $fillable = ['material','apresentacao','custo','qtd_calculada','deleted','alt_user_id'];
}
