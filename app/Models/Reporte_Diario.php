<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte_Diario extends Model
{
    protected $table = 'gen_rep_diario';
    // protected $primaryKey = 'gen_repd_id ';
    //public $timestamps = false;
    protected $fillable = [
     
        'gen_repd_id',
        'gen_repd_fecha',
        //'gen_repd_carga_max_c',
        'gen_repd_gen_bruta',
        'gen_repd_cons_propio' ,
        'gen_repd_gen_neta' ,
        'gen_repd_cap_nominal',
        'gen_repd_cap_efectiva',
        'gen_repd_carg_promedio' ,
        'gen_repd_indice_carg_promed_nominal',
        'gen_repd_hrs_operacion',
        'gen_repd_hrs_disponibilidad',
        'gen_repd_disp_generador',
        'gen_repd_cons_combustible_lts',
        'gen_repd_cons_combustible_gal',
        'gen_repd_efi_comb_bruta',
        'gen_repd_efi_comb_neta',
        'gen_repd_con_comb_esp_bruto',
        'gen_repd_cons_comb_esp_neto',
        'gen_repd_cons_aceite_gal',
        'gen_repd_cons_aceite_lts',
        'gen_repd_hrs_trab_motor_tc',
        'gen_repd_hrs_last_overhaul',
        'gen_repd_hrs_last_mantenimiento',
        'gen_repd_hrs_trab_ace_lub_motor' ,
        'gen_maquina_gen_ma_id'

    ];
}
