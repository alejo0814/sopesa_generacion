<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro_aceite extends Model
{
    protected $table = 'gen_registro_aceite';
    protected $primaryKey = 'gen_rea_id';
    //public $timestamps = false;
    protected $fillable = [
        'gen_rea_id',
        'gen_rea_fecha',
        'gen_rea_lectura',
        'gen_rea_consumo',
        'gen_maquina_gen_ma_id'
    ];
}
