<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro_overhaul extends Model
{

    protected $table = 'gen_overhaul';
    // protected $primaryKey = 'gen_repd_id ';
    //public $timestamps = false;
    protected $fillable = [ 
        'gen_over_fecha',
        'gen_maquina_gen_ma_id'
    ];
}
