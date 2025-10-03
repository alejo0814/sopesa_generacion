<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ciudad;

class Registro_carga_max extends Model
{
   
    protected $table = 'gen_carga_maxima';
    public $timestamps = true;
    protected $primaryKey = 'gen_cm_id';
    protected $keyType = 'int'; // Tipo de dato de la clave primaria
    public $incrementing = true; // Asegúrate de que el incremento automático esté activado
    protected $fillable = [ 
       
        'gen_cm_fecha',	
        'gen_cm_value',	
        'gen_cm_hora',		
        'gen_ciudad_gen_ci_id',
    ];
}
