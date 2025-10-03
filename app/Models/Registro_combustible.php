<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro_combustible extends Model
{
    protected $table = 'gen_registro_combustible';
    // protected $primaryKey = 'gen_repd_id ';
    //public $timestamps = false;
    protected $fillable = [
        'gen_rec_id',
        'gen_rec_fecha',
        'gen_rec_lectura',
        'gen_rec_consumno',
        'gen_maquina_gen_ma_id'
    ];
}
