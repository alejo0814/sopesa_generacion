<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Reporte_Diario;
use App\Models\Registro_hrs_trabajadas;
use App\Models\Registro_aceite;
use App\Models\Registro_combustible;
use App\Models\Planta;

class GraficasGeneralesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $plantas = Planta::all();
        // Realiza la consulta a la base de datos
        //  $datos = Reporte_Diario::select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad','gen_maquina_gen_ma_id')->get();

        /*   $datos_man_1 = Reporte_Diario::where('gen_repd_fecha', 'gen_repd_hrs_disponibilidad','gen_maquina_gen_ma_id')
           ->where('gen_maquina_gen_ma_id', '=', 1)
           ->get(); */

        /* $datos_man_1 = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 1)
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id')
            ->get();

        $datos_man_2 = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 2)
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id')
            ->get(); */
        /* 
           $datos_man_2 = Reporte_Diario::where('gen_repd_fecha', 'gen_repd_hrs_disponibilidad','gen_maquina_gen_ma_id')
           ->where('gen_maquina_gen_ma_id', '=', 2)
           ->get(); */
        /*      
            $datos_hrs_acum_1 = Registro_hrs_trabajadas::select('gen_reh_hora_acum')->get();
        $datos_hrs_acum_2 = Registro_hrs_trabajadas::select('gen_reh_hora_acum')->get();
 */



        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        //$startOfLastMonth = Carbon::now()->startOfMonth();
        //$endOfLastMonth = Carbon::now()->endOfMonth();

        $datos_ultimo_mes1 = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 1)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();

        $datos_ultimo_mes2 = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 2)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();




        $hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 1)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        $hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 2)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();


        // Prepara los datos para Chart.js
        $labels1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
        $values1 = $datos_ultimo_mes1->pluck('gen_repd_hrs_disponibilidad');
        $labels2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
        $values2 = $datos_ultimo_mes2->pluck('gen_repd_hrs_disponibilidad');

        // Obtener los datos de horas acumuladas para MAN 1
        /*  $datos_hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', operator: 1)
            ->sum('gen_reh_hora_acum'); // Usar sum para obtener el valor numérico */
        $datos_hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 1)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        // Obtener los datos de horas acumuladas para MAN 2
        /* $datos_hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 2)
            ->sum('gen_reh_hora_acum'); // Usar sum para obtener el valor numérico */
        $datos_hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 2)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();
        // Calcular el porcentaje
        // $hrs_acum_porce_1 = ($datos_hrs_acum_1 / 1000) * 100;
        // $hrs_acum_porce_2 = ($datos_hrs_acum_2 / 1000) * 100;
        // Verificar si se encontraron registros antes de acceder a los atributos
        $hrs_acum_porce_1 = $datos_hrs_acum_1 ? ($datos_hrs_acum_1->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        $hrs_acum_porce_2 = $datos_hrs_acum_2 ? ($datos_hrs_acum_2->gen_registro_hora_ult_rep / 18000) * 100 : 0;

        $hrs_acum1 = $datos_hrs_acum_1->gen_registro_hora_ult_rep;
        //$hrs_acum_porce_1 = ($datos_hrs_acum_1 / 1000) * 100;
        $hrs_acum2 = $datos_hrs_acum_2->gen_registro_hora_ult_rep;
        //$hrs_acum_porce_2 = ($datos_hrs_acum_2 / 1000) * 100;





        //datos para el combustible

        $datos_comb_acum_1 = Registro_combustible::where('gen_maquina_gen_ma_id', 1)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');
        //->first(['gen_rec_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);

        $datos_comb_acum_2 = Registro_combustible::where('gen_maquina_gen_ma_id', 2)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');
        // ->first(['gen_rec_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);


        $comb_acum1 = $datos_comb_acum_1->pluck('gen_rec_consumno');
        $fech_comb1 = $datos_comb_acum_1->pluck('gen_rec_fecha');

        $comb_acum2 = $datos_comb_acum_2->pluck('gen_rec_consumno');
        $fech_comb2 = $datos_comb_acum_2->pluck('gen_rec_fecha');

        //datos combustible litros
        $fecha_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
        $comb_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_cons_combustible_lts');
        $fecha_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
        $comb_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_cons_combustible_lts');








        //datos para el aceite

        $datos_aceite_acum_1 = Registro_aceite::where('gen_maquina_gen_ma_id', 1)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');
        //->first(['gen_rea_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);

        $datos_aceite_acum_2 = Registro_aceite::where('gen_maquina_gen_ma_id', 2)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');
        // ->first(['gen_rea_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);


        $aceite_acum1 = $datos_aceite_acum_1->pluck('gen_rea_consumo');
        $fech_aceite1 = $datos_aceite_acum_1->pluck('gen_rea_fecha');

        $aceite_acum2 = $datos_aceite_acum_2->pluck('gen_rea_consumo');
        $fech_aceite2 = $datos_aceite_acum_2->pluck('gen_rea_fecha');

        //datos combustible litros
        //$fecha_lts_aceite_1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
        //$aceite_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_cons_combustible_lts');
        //$fecha_lts_aceite_2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
        //$aceite_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_cons_combustible_lts');


        //dd(compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2','hrs_acum1','hrs_acum1', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2','fecha_lts_1','comb_lts_1','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2'));





        //datos emd

        $datos_ultimo_mes1_EMD = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 5)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();

        $datos_ultimo_mes2_EMD = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 6)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();
        $datos_ultimo_mes3_EMD = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 7)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();

        $datos_ultimo_mes4_EMD = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 8)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();

        $datos_ultimo_mes5_EMD = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 9)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();

        $datos_ultimo_mes6_EMD = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 10)
            ->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
            ->get();




        $hrs_acum_1_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 5)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        $hrs_acum_2_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 6)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

            $hrs_acum_3_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 7)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        $hrs_acum_4_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 8)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

            $hrs_acum_5_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 9)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        $hrs_acum_6_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 10)
            ->select('gen_reh_hora_acum')
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();
        // Prepara los datos para Chart.js
        $labels1_EMD = $datos_ultimo_mes1_EMD->pluck('gen_repd_fecha');
        $values1_EMD = $datos_ultimo_mes1_EMD->pluck('gen_repd_hrs_disponibilidad');
        $labels2_EMD = $datos_ultimo_mes2_EMD->pluck('gen_repd_fecha');
        $values2_EMD = $datos_ultimo_mes2_EMD->pluck('gen_repd_hrs_disponibilidad');
        $labels3_EMD = $datos_ultimo_mes3_EMD->pluck('gen_repd_fecha');
        $values3_EMD = $datos_ultimo_mes3_EMD->pluck('gen_repd_hrs_disponibilidad');
        $labels4_EMD = $datos_ultimo_mes4_EMD->pluck('gen_repd_fecha');
        $values4_EMD = $datos_ultimo_mes4_EMD->pluck('gen_repd_hrs_disponibilidad');
        $labels5_EMD = $datos_ultimo_mes5_EMD->pluck('gen_repd_fecha');
        $values5_EMD = $datos_ultimo_mes5_EMD->pluck('gen_repd_hrs_disponibilidad');
        $labels6_EMD = $datos_ultimo_mes6_EMD->pluck('gen_repd_fecha');
        $values6_EMD = $datos_ultimo_mes6_EMD->pluck('gen_repd_hrs_disponibilidad');



        // Obtener los datos de horas acumuladas para MAN 1
        $datos_hrs_acum_1_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 5)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        // Obtener los datos de horas acumuladas para MAN 2

        $datos_hrs_acum_2_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 6)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        $datos_hrs_acum_3_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 7)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();  
        $datos_hrs_acum_4_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 8)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();
        $datos_hrs_acum_5_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 9)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();
        $datos_hrs_acum_6_EMD = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 10)
            ->orderBy('gen_reh_fecha', 'desc')
            ->first();

        // Calcular el porcentaje

        // Verificar si se encontraron registros antes de acceder a los atributos
        $hrs_acum_porce_1_EMD = $datos_hrs_acum_1_EMD ? ($datos_hrs_acum_1_EMD->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        $hrs_acum_porce_2_EMD = $datos_hrs_acum_2_EMD ? ($datos_hrs_acum_2_EMD->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        $hrs_acum_porce_3_EMD = $datos_hrs_acum_3_EMD ? ($datos_hrs_acum_3_EMD->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        $hrs_acum_porce_4_EMD = $datos_hrs_acum_4_EMD ? ($datos_hrs_acum_4_EMD->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        $hrs_acum_porce_5_EMD = $datos_hrs_acum_5_EMD ? ($datos_hrs_acum_5_EMD->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        $hrs_acum_porce_6_EMD = $datos_hrs_acum_6_EMD ? ($datos_hrs_acum_6_EMD->gen_registro_hora_ult_rep / 18000) * 100 : 0;

        $hrs_acum1_EMD = $datos_hrs_acum_1_EMD ? $datos_hrs_acum_1_EMD->gen_registro_hora_ult_rep : 0;

        $hrs_acum2_EMD = $datos_hrs_acum_2_EMD ? $datos_hrs_acum_2_EMD->gen_registro_hora_ult_rep : 0;
        $hrs_acum3_EMD = $datos_hrs_acum_3_EMD ? $datos_hrs_acum_3_EMD->gen_registro_hora_ult_rep : 0;
        $hrs_acum4_EMD = $datos_hrs_acum_4_EMD ? $datos_hrs_acum_4_EMD->gen_registro_hora_ult_rep : 0;
        $hrs_acum5_EMD = $datos_hrs_acum_5_EMD ? $datos_hrs_acum_5_EMD->gen_registro_hora_ult_rep : 0;
        $hrs_acum6_EMD = $datos_hrs_acum_6_EMD ? $datos_hrs_acum_6_EMD->gen_registro_hora_ult_rep : 0;

        //datos para el combustible

        $datos_comb_acum_1_EMD = Registro_combustible::where('gen_maquina_gen_ma_id', 5)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');

        $datos_comb_acum_2_EMD = Registro_combustible::where('gen_maquina_gen_ma_id', 6)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');

        $datos_comb_acum_3_EMD = Registro_combustible::where('gen_maquina_gen_ma_id', 7)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');
        $datos_comb_acum_4_EMD = Registro_combustible::where('gen_maquina_gen_ma_id', 8)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');
        $datos_comb_acum_5_EMD = Registro_combustible::where('gen_maquina_gen_ma_id', 9)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');
        $datos_comb_acum_6_EMD = Registro_combustible::where('gen_maquina_gen_ma_id', 10)
            ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rec_fecha', 'asc');

        $comb_acum1_EMD = $datos_comb_acum_1_EMD->pluck('gen_rec_consumno');
        $fech_comb1_EMD = $datos_comb_acum_1_EMD->pluck('gen_rec_fecha');

        $comb_acum2_EMD = $datos_comb_acum_2_EMD->pluck('gen_rec_consumno');
        $fech_comb2_EMD = $datos_comb_acum_2_EMD->pluck('gen_rec_fecha');
        $comb_acum3_EMD = $datos_comb_acum_3_EMD->pluck('gen_rec_consumno');
        $fech_comb3_EMD = $datos_comb_acum_3_EMD->pluck('gen_rec_fecha');
        $comb_acum4_EMD = $datos_comb_acum_4_EMD->pluck('gen_rec_consumno');
        $fech_comb4_EMD = $datos_comb_acum_4_EMD->pluck('gen_rec_fecha');
        $comb_acum5_EMD = $datos_comb_acum_5_EMD->pluck('gen_rec_consumno');
        $fech_comb5_EMD = $datos_comb_acum_5_EMD->pluck('gen_rec_fecha');
        $comb_acum6_EMD = $datos_comb_acum_6_EMD->pluck('gen_rec_consumno');
        $fech_comb6_EMD = $datos_comb_acum_6_EMD->pluck('gen_rec_fecha');

        //datos combustible litros
        $fecha_lts_1_EMD = $datos_ultimo_mes1_EMD->pluck('gen_repd_fecha');
        $comb_lts_1_EMD = $datos_ultimo_mes1_EMD->pluck('gen_repd_cons_combustible_lts');
        $fecha_lts_2_EMD = $datos_ultimo_mes2_EMD->pluck('gen_repd_fecha');
        $comb_lts_2_EMD = $datos_ultimo_mes2_EMD->pluck('gen_repd_cons_combustible_lts');
        $fecha_lts_3_EMD = $datos_ultimo_mes3_EMD->pluck('gen_repd_fecha');
        $comb_lts_3_EMD = $datos_ultimo_mes3_EMD->pluck('gen_repd_cons_combustible_lts');
        $fecha_lts_4_EMD = $datos_ultimo_mes4_EMD->pluck('gen_repd_fecha');
        $comb_lts_4_EMD = $datos_ultimo_mes4_EMD->pluck('gen_repd_cons_combustible_lts');
        $fecha_lts_5_EMD = $datos_ultimo_mes5_EMD->pluck('gen_repd_fecha');
        $comb_lts_5_EMD = $datos_ultimo_mes5_EMD->pluck('gen_repd_cons_combustible_lts');
        $fecha_lts_6_EMD = $datos_ultimo_mes6_EMD->pluck('gen_repd_fecha');
        $comb_lts_6_EMD = $datos_ultimo_mes6_EMD->pluck('gen_repd_cons_combustible_lts');

        //datos para el aceite

        $datos_aceite_acum_1_EMD = Registro_aceite::where('gen_maquina_gen_ma_id', 5)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');


        $datos_aceite_acum_2_EMD = Registro_aceite::where('gen_maquina_gen_ma_id', 6)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');

        $datos_aceite_acum_3_EMD = Registro_aceite::where('gen_maquina_gen_ma_id', 7)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');
        $datos_aceite_acum_4_EMD = Registro_aceite::where('gen_maquina_gen_ma_id', 8)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');
        $datos_aceite_acum_5_EMD = Registro_aceite::where('gen_maquina_gen_ma_id', 9)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');
        $datos_aceite_acum_6_EMD = Registro_aceite::where('gen_maquina_gen_ma_id', 10)
            ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
            ->orderBy('gen_rea_fecha', 'asc');


        $aceite_acum1 = $datos_aceite_acum_1_EMD->pluck('gen_rea_consumo');
        $fech_aceite1 = $datos_aceite_acum_1_EMD->pluck('gen_rea_fecha');

        $aceite_acum2 = $datos_aceite_acum_2_EMD->pluck('gen_rea_consumo');
        $fech_aceite2 = $datos_aceite_acum_2_EMD->pluck('gen_rea_fecha');

        $aceite_acum3 = $datos_aceite_acum_3_EMD->pluck('gen_rea_consumo');
        $fech_aceite3 = $datos_aceite_acum_3_EMD->pluck('gen_rea_fecha');
        $aceite_acum4 = $datos_aceite_acum_4_EMD->pluck('gen_rea_consumo');
        $fech_aceite4 = $datos_aceite_acum_4_EMD->pluck('gen_rea_fecha');
        $aceite_acum5 = $datos_aceite_acum_5_EMD->pluck('gen_rea_consumo');
        $fech_aceite5 = $datos_aceite_acum_5_EMD->pluck('gen_rea_fecha');
        $aceite_acum6 = $datos_aceite_acum_6_EMD->pluck('gen_rea_consumo');
        $fech_aceite6 = $datos_aceite_acum_6_EMD->pluck('gen_rea_fecha');

        /*  dd(compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2','hrs_acum1','hrs_acum2', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2','fecha_lts_1','comb_lts_1','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2','plantas','labels1_EMD', 'values1_EMD', 'labels2_EMD', 'values2_EMD', 'hrs_acum_1_EMD', 'hrs_acum_porce_1_EMD', 'hrs_acum_2_EMD','hrs_acum1_EMD','hrs_acum2_EMD', 'hrs_acum_porce_2_EMD', 'comb_acum1_EMD', 'comb_acum2_EMD', 'fech_comb1_EMD', 'fech_comb2_EMD','fecha_lts_1_EMD','comb_lts_1_EMD','comb_lts_2_EMD','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2'));
        */
        /* return view('dash.graf_generales', compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2', 'hrs_acum1', 'hrs_acum2', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2', 'fecha_lts_1', 'comb_lts_1', 'comb_lts_2', 'aceite_acum1', 'fech_aceite1', 'aceite_acum2', 'fech_aceite2', 'plantas', 'labels1_EMD', 'values1_EMD', 'labels2_EMD', 'values2_EMD', 'hrs_acum_1_EMD', 'hrs_acum_porce_1_EMD', 'hrs_acum_2_EMD', 'hrs_acum1_EMD', 'hrs_acum2_EMD', 'hrs_acum_porce_2_EMD', 'comb_acum1_EMD', 'comb_acum2_EMD', 'fech_comb1_EMD', 'fech_comb2_EMD', 'fecha_lts_1_EMD', 'comb_lts_1_EMD', 'comb_lts_2_EMD', 'aceite_acum1', 'fech_aceite1', 'aceite_acum2', 'fech_aceite2')); */
        
        return view('dash.graf_generales', compact('labels1','values1','labels2','values2','hrs_acum_1','hrs_acum_porce_1','hrs_acum_2','hrs_acum1','hrs_acum2','hrs_acum_porce_2','comb_acum1','comb_acum2','fech_comb1','fech_comb2','fecha_lts_1','comb_lts_1','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2','plantas','labels1_EMD','values1_EMD','labels2_EMD','values2_EMD','labels3_EMD','values3_EMD','labels4_EMD','values4_EMD','labels5_EMD','values5_EMD','labels6_EMD','values6_EMD','hrs_acum_1_EMD','hrs_acum_porce_1_EMD','hrs_acum1_EMD','comb_acum1_EMD','fech_comb1_EMD','fecha_lts_1_EMD','comb_lts_1_EMD','hrs_acum_2_EMD','hrs_acum2_EMD','hrs_acum_porce_2_EMD','comb_acum2_EMD','fech_comb2_EMD','comb_lts_2_EMD','hrs_acum_3_EMD','hrs_acum3_EMD','hrs_acum_porce_3_EMD','comb_acum3_EMD','fech_comb3_EMD','comb_lts_3_EMD','hrs_acum_4_EMD','hrs_acum4_EMD','hrs_acum_porce_4_EMD','comb_acum4_EMD','fech_comb4_EMD','comb_lts_4_EMD','hrs_acum_5_EMD','hrs_acum5_EMD','hrs_acum_porce_5_EMD','comb_acum5_EMD','fech_comb5_EMD','comb_lts_5_EMD','hrs_acum_6_EMD','hrs_acum6_EMD','hrs_acum_porce_6_EMD','comb_acum6_EMD','fech_comb6_EMD','comb_lts_6_EMD'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }




    public function cons_mes(Request $request)
    {

        $plantas = Planta::all();


        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data = $request->validate([
                'fecha_m' => 'required|date', // Cambiar a string para pruebas
                'planta_id' => 'required|string',
            ], [
                'fecha_m.required' => 'La fecha es obligatoria.',
                'fecha_m.date' => 'La fecha debe ser un formato valido.',

                'planta_id.required' => 'La planta es obligatoria.',
                'planta_id.string' => 'La planta debe ser un texto valido.',

            ]);

            // Lógica para manejar la consulta
            $fecha = $request->input('fecha_m');
            $plantaId = $request->input('planta_id');

            list($year, $month) = explode('-', $fecha);
            //echo "Año: " . $year; // Salida: "Año: 2025"
            //echo "Mes: " . $month; // Salida: "Mes: 02"
            ////echo "planta: " . $planta; // Salida: "Mes: 02"

            // Aquí puedes agregar la lógica para procesar los datos y devolver una respuesta
            //return view('resultado', compact('fecha', 'plantaId'));


            // Realiza la consulta a la base de datos

            $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

            //$startOfLastMonth = Carbon::now()->startOfMonth();
            //$endOfLastMonth = Carbon::now()->endOfMonth();


            $datos_ultimo_mes1 = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 1)
                //->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
                ->whereMonth('gen_repd_fecha', $month)
                ->whereYear('gen_repd_fecha', $year)
                ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
                ->get();

            $datos_ultimo_mes2 = Reporte_Diario::where('gen_maquina_gen_ma_id', '=', 2)
                //->whereBetween('gen_repd_fecha', [$startOfLastMonth, $endOfLastMonth])
                ->whereMonth('gen_repd_fecha', $month)
                ->whereYear('gen_repd_fecha', $year)
                ->select('gen_repd_fecha', 'gen_repd_hrs_disponibilidad', 'gen_maquina_gen_ma_id', 'gen_repd_cons_combustible_lts')
                ->get();



            $hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 1)
                ->select('gen_reh_hora_acum')
                ->orderBy('gen_reh_fecha', 'desc')
                ->first();

            $hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 2)
                ->select('gen_reh_hora_acum')
                ->orderBy('gen_reh_fecha', 'desc')
                ->first();


            // Prepara los datos para Chart.js
            $labels1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
            $values1 = $datos_ultimo_mes1->pluck('gen_repd_hrs_disponibilidad');
            $labels2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
            $values2 = $datos_ultimo_mes2->pluck('gen_repd_hrs_disponibilidad');

            // Obtener los datos de horas acumuladas para MAN 1

            $datos_hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 1)
                ->orderBy('gen_reh_fecha', 'desc')
                ->first();

            // Obtener los datos de horas acumuladas para MAN 2

            $datos_hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 2)
                ->orderBy('gen_reh_fecha', 'desc')
                ->first();
            // Calcular el porcentaje
            // $hrs_acum_porce_1 = ($datos_hrs_acum_1 / 1000) * 100;
            // $hrs_acum_porce_2 = ($datos_hrs_acum_2 / 1000) * 100;
            // Verificar si se encontraron registros antes de acceder a los atributos
            $hrs_acum_porce_1 = $datos_hrs_acum_1 ? ($datos_hrs_acum_1->gen_registro_hora_ult_rep / 18000) * 100 : 0;
            $hrs_acum_porce_2 = $datos_hrs_acum_2 ? ($datos_hrs_acum_2->gen_registro_hora_ult_rep / 18000) * 100 : 0;

            $hrs_acum1 = $datos_hrs_acum_1->gen_registro_hora_ult_rep;
            //$hrs_acum_porce_1 = ($datos_hrs_acum_1 / 1000) * 100;
            $hrs_acum2 = $datos_hrs_acum_2->gen_registro_hora_ult_rep;
            //$hrs_acum_porce_2 = ($datos_hrs_acum_2 / 1000) * 100;





            //datos para el combustible

            $datos_comb_acum_1 = Registro_combustible::where('gen_maquina_gen_ma_id', 1)
                //  ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
                ->whereMonth('gen_rec_fecha', $month)
                ->whereYear('gen_rec_fecha', $year)
                ->orderBy('gen_rec_fecha', 'asc');
            //->first(['gen_rec_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);

            $datos_comb_acum_2 = Registro_combustible::where('gen_maquina_gen_ma_id', 2)
                //->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
                ->whereMonth('gen_rec_fecha', $month)
                ->whereYear('gen_rec_fecha', $year)
                ->orderBy('gen_rec_fecha', 'asc');
            // ->first(['gen_rec_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);


            $comb_acum1 = $datos_comb_acum_1->pluck('gen_rec_consumno');
            $fech_comb1 = $datos_comb_acum_1->pluck('gen_rec_fecha');

            $comb_acum2 = $datos_comb_acum_2->pluck('gen_rec_consumno');
            $fech_comb2 = $datos_comb_acum_2->pluck('gen_rec_fecha');

            //datos combustible litros
            $fecha_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
            $comb_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_cons_combustible_lts');
            $fecha_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
            $comb_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_cons_combustible_lts');




            //datos para el aceite

            $datos_aceite_acum_1 = Registro_aceite::where('gen_maquina_gen_ma_id', 1)
                ->whereMonth('gen_rea_fecha', $month)
                ->whereYear('gen_rea_fecha', $year)
                // ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
                ->orderBy('gen_rea_fecha', 'asc');
            //->first(['gen_rea_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);

            $datos_aceite_acum_2 = Registro_aceite::where('gen_maquina_gen_ma_id', 2)
                ->whereMonth('gen_rea_fecha', $month)
                ->whereYear('gen_rea_fecha', $year)
                //->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
                ->orderBy('gen_rea_fecha', 'asc');
            // ->first(['gen_rea_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);


            $aceite_acum1 = $datos_aceite_acum_1->pluck('gen_rea_consumo');
            $fech_aceite1 = $datos_aceite_acum_1->pluck('gen_rea_fecha');

            $aceite_acum2 = $datos_aceite_acum_2->pluck('gen_rea_consumo');
            $fech_aceite2 = $datos_aceite_acum_2->pluck('gen_rea_fecha');


            // dd(compact('fecha','year','month','labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2','hrs_acum1','hrs_acum1', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2','fecha_lts_1','comb_lts_1','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2'));

            DB::commit();
            return view('dash.graf_generales', compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2', 'hrs_acum1', 'hrs_acum2', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2', 'fecha_lts_1', 'comb_lts_1', 'comb_lts_2', 'aceite_acum1', 'fech_aceite1', 'aceite_acum2', 'fech_aceite2', 'plantas'));
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            Log::info('Request method: ' . $request->method());
            Log::info('Request method at start: ' . $request->method());
            // Código de validación y lógica
            Log::info('Request method after validation: ' . $request->method());

            return redirect()->back()->withErrors($e->errors())->withInput();

            //return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            Log::info('Request method: ' . $request->method());
            Log::info('Request method at start: ' . $request->method());
            // Código de validación y lógica
            Log::info('Request method after validation: ' . $request->method());
            return redirect()->back()->with('error', 'Error al guardar los datos');
        }






        //return response()->json(['success' => 'Datos guardados correctamente.'], 200);
        /*     } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            return response()->json(['error' => 'Error de validación', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar los datos'], 500);
        } */
    }
}
