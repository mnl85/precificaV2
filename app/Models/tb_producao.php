<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_producao extends Model
{
    use HasFactory;
    protected $fillable = ['id_trabalho','quantidade','valor_cobrado','empresa_id','anomes','alt_user_id','deleted'];
    protected $table = 'tb_producao';

    public function fc_trabalho()
    {
        return $this->belongsTo(tb_meus_trabalhos::class, 'id_trabalho', 'id');
    }

}
