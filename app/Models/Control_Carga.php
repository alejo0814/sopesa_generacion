<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Control_Carga extends Model
{
    protected $table = 'gen_control_carga';
    protected $primaryKey = 'gen_cg_id';
    //public $timestamps = false;
    protected $fillable = [
        'gen_cg_id',
        'gen_cg_fecha',
        'gen_cg_hora',
        'gen_cg_valor_kw',
        'gen_maquina_gen_ma_id'
    ];
}


