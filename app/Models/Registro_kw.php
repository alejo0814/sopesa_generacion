<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro_kw extends Model
{
    protected $table = 'gen_registro_kw';
    // protected $primaryKey = 'gen_repd_id ';
    //public $timestamps = false;
    protected $fillable = [
        'gen_rek_id',
        'gen_rek_fecha',
        'gen_rek_lectura',
        'gen_rek_gen_act',
        'gen_maquina_gen_ma_id',
    ];
}
