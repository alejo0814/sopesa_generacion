<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro_hrs_trabajadas extends Model
{
    protected $table = 'gen_registro_horas';
    // protected $primaryKey = 'gen_repd_id ';
    //public $timestamps = false;
    protected $fillable = [
        'gen_reh_id',
        'gen_reh_fecha',
        'gen_reh_hora_trabajada',
        'gen_reh_hora_disponible',
        'gen_reh_hora_actuales',
        'gen_reh_hora_acum',
        'gen_registro_hora_ult_rep',
        'gen_maquina_gen_ma_id',
       
    ];
}
