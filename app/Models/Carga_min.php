<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carga_min extends Model
{
    
   
    protected $table = 'gen_carga_min';
    public $timestamps = true;
    protected $primaryKey = 'gen_cmi_id';
    protected $keyType = 'int'; // Tipo de dato de la clave primaria
    public $incrementing = true; // Asegúrate de que el incremento automático esté activado
    protected $fillable = [ 
       
        'gen_cmi_fecha',	
        'gen_cmi_value',	
        'gen_cmi_hora',		
        'gen_ciudad_gen_ci_id',
    ];
}
