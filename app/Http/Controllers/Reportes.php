<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reporte_Diario;
use App\Models\Planta;
use App\Models\Generador;
use App\Models\Registro_carga_max;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Contracts\Service\Attribute\Required;
use Carbon\Carbon;

class Reportes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        $rep = Reporte_Diario::all();
        $data1 = Reporte_Diario::all();
        $plantas = Planta::all();
        $last_rep = DB::table('gen_rep_diario')
            ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')

            ->select('*')
            ->where('gen_repd_fecha', DB::raw('(select MAX(gen_repd_fecha) from gen_rep_diario)'))
            ->orderBy('gen_maquina_gen_ma_id', 'ASC')
            ->get();

        $cmm = DB::table('gen_rep_diario')
            ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
            ->join('gen_carga_maxima', 'gen_rep_diario.gen_repd_fecha', '=', 'gen_carga_maxima.gen_cm_fecha')
            ->select('*')
            ->where('gen_repd_fecha', DB::raw('(select MAX(gen_repd_fecha) from gen_rep_diario)'))
            ->orderBy('gen_maquina_gen_ma_id', 'ASC')
            ->get();
        // $carg_max = Registro_carga_max::all();
        $cm = DB::table('gen_carga_maxima')
            ->select('*')
            ->where('gen_ciudad_gen_ci_id', 1)
            ->orderBy('gen_cm_fecha', 'desc')
            ->get();
        // $carg_max = Registro_carga_max::where('gen_ciudad_gen_ci_id', '1')->latest()->first();
        // $query = Registro_carga_max::where('gen_ciudad_gen_ci_id', '1')->latest();
        // dd($query);
        //dd($sql, $bindings, $result);
        /* 
        $results = DB::table('gen_rep_diario as a')
            ->join('gen_maquina as b', 'a.gen_maquina_gen_ma_id', '=', 'b.gen_ma_id')
            ->select('a.*', 'b.gen_ma_nombre')
            ->get(); */

        return view('reportes.crud', compact('data1', 'plantas', 'rep', 'last_rep', 'cmm'));
    }
    public function getGeneradores($planta_id)
    {
        $generadores = Generador::where('gen_planta_gen_pl_id', $planta_id)->get();
        return response()->json($generadores);
    }





    public function busqueda_rep_d(Request $request)
    {

        // $año = $request->get('año');
        $planta = $request->input('planta_id');
        $generador = $request->get('generador_id');
        $fecha = $request->get('fecha');

        $resul_rep_fecha = DB::table('gen_rep_diario as a')
            ->join('gen_maquina as c', 'a.gen_maquina_gen_ma_id', '=', 'c.gen_ma_id')
            ->join('gen_planta as b', 'c.gen_planta_gen_pl_id', '=', 'b.gen_pl_id')
            ->select('a.*', 'b.gen_pl_nombre', 'c.gen_ma_nombre')
            ->where('a.gen_repd_fecha',  $fecha)
            ->where('b.gen_pl_id', $planta)
            ->get();
        $cm = DB::table('gen_carga_maxima')
            ->select('*')
            ->where('gen_ciudad_gen_ci_id', 1)
            ->where('gen_cm_fecha', operator: $fecha)
            ->orderBy('gen_cm_fecha', 'desc')
            ->get();



            if ($planta == 1) {
                 return view('reportes.rep_diario')->with('resul_rep_fecha', $resul_rep_fecha)->with('cm', $cm);
            } else {
                return view('reportes.rep_diario_emd')->with('resul_rep_fecha', $resul_rep_fecha)->with('cm', $cm);
            }
            
       
        //return view('publicaciones.pub_busqueda', ['valorSeleccionado' => $valorSeleccionado]);
        // return view('reportes.rep_diario', ['resul_rep_fecha' => $resul_rep_fecha]);
        //return response()->json(['est_123'=> $est_123], $publi_año);

    }

    public function reporteMensual(Request $request)
    {

        //$mes = $request->input('mes');
        //$año = $request->input('año');
        $rep = Reporte_Diario::all();
       // $data1 = Reporte_Diario::all();
        $plantas = Planta::all();
        // Obtener el mes y el año actual utilizando Carbon
        $fechaActual = Carbon::now();
        $mesActual = $fechaActual->month; // Mes actual en formato numérico (1-12)
        $añoActual = $fechaActual->year;  // Año actual en formato numérico (2025)


        $cmm = Registro_carga_max::select(
            DB::raw('SUM(gen_carga_maxima.gen_cm_value) as gen_cm_value'))
        //->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
        //->join('gen_carga_maxima', 'gen_rep_diario.gen_repd_fecha', '=', 'gen_carga_maxima.gen_cm_fecha')
        //->select('*')
        ->whereMonth('gen_cm_fecha', $mesActual)
        ->whereYear('gen_cm_fecha', $añoActual)
        //->orderBy('gen_maquina_gen_ma_id', 'ASC')
        ->get();

        $resul_rep_mensual =  Reporte_Diario::select(
            DB::raw('SUM(gen_repd_carga_max_c) as gen_repd_carga_max_c'),
            DB::raw('SUM(gen_repd_gen_bruta) as gen_repd_gen_bruta'),
            DB::raw('SUM(gen_repd_cons_propio) as gen_repd_cons_propio'),
            DB::raw('SUM(gen_repd_gen_neta) as gen_repd_gen_neta'),
            DB::raw('SUM(gen_repd_cap_nominal) as gen_repd_cap_nominal'),
            DB::raw('SUM(gen_repd_cap_efectiva) as gen_repd_cap_efectiva'),
            DB::raw('SUM(gen_repd_carg_promedio) as gen_repd_carg_promedio'),
            DB::raw('SUM(gen_repd_indice_carg_promed_nominal) as gen_repd_indice_carg_promed_nominal'),
            DB::raw('SUM(gen_repd_hrs_operacion) as gen_repd_hrs_operacion'),
            DB::raw('SUM(gen_repd_hrs_disponibilidad) as gen_repd_hrs_disponibilidad'),
            DB::raw('SUM(gen_repd_disp_generador) as gen_repd_disp_generador'),
            DB::raw('SUM(gen_repd_cons_combustible_lts) as gen_repd_cons_combustible_lts'),
            DB::raw('SUM(gen_repd_cons_combustible_gal) as gen_repd_cons_combustible_gal'),
            DB::raw('SUM(gen_repd_efi_comb_bruta) as gen_repd_efi_comb_bruta'),
            DB::raw('SUM(gen_repd_efi_comb_neta) as gen_repd_efi_comb_neta'),
            DB::raw('SUM(gen_repd_con_comb_esp_bruto) as gen_repd_con_comb_esp_bruto'),
            DB::raw('SUM(gen_repd_cons_comb_esp_neto) as gen_repd_cons_comb_esp_neto'),
            DB::raw('SUM(gen_repd_cons_aceite_gal) as gen_repd_cons_aceite_gal'),
            DB::raw('SUM(gen_repd_cons_aceite_lts) as gen_repd_cons_aceite_lts'),
            DB::raw('SUM(gen_repd_hrs_trab_motor_tc) as gen_repd_hrs_trab_motor_tc'),
            DB::raw('SUM(gen_repd_hrs_last_overhaul) as gen_repd_hrs_last_overhaul'),
            DB::raw('SUM(gen_repd_hrs_last_mantenimiento) as gen_repd_hrs_last_mantenimiento'),
            DB::raw('SUM(gen_repd_hrs_trab_ace_lub_motor) as gen_repd_hrs_trab_ace_lub_motor'),
            'gen_maquina.gen_ma_id',
            'gen_maquina.gen_ma_nombre',
            DB::raw('MONTH(gen_repd_fecha) as mes'),
            DB::raw('YEAR(gen_repd_fecha) as año')
        )
        ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
        ->whereMonth('gen_repd_fecha', $añoActual)
        ->whereYear('gen_repd_fecha', $mesActual)
        ->groupBy('gen_maquina.gen_ma_id', 'año', 'mes','gen_maquina.gen_ma_nombre',)
        ->get();

        //return view('reportes.rep_mensual')->with('resul_rep_mensual', $resul_rep_mensual);        
        //dd(compact('cmm','resul_rep_mensual', 'plantas', 'rep'));
        return view('reportes.rep_mensual', compact('cmm','resul_rep_mensual', 'plantas', 'rep'));
    }
    public function busqueda_rep_m(Request $request)
    {
        $plantas = Planta::all();
        //$planta = $request->input('planta_id');
        $planta = $request->get('planta_id');
        //$generador = $request->get('generador_id');
        $fecha = $request->get('fecha');
        // $año = $request->get('año');
        $fecha_m= $request->input('fecha_m');

        list($year, $month) = explode('-', $fecha_m);
        echo "Año: " . $year; // Salida: "Año: 2025"
        echo "Mes: " . $month; // Salida: "Mes: 02"
        echo "planta: " . $planta; // Salida: "Mes: 02"


        $cmm = Registro_carga_max::select(
            DB::raw('SUM(gen_carga_maxima.gen_cm_value) as gen_cm_value'))
        //->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
        //->join('gen_carga_maxima', 'gen_rep_diario.gen_repd_fecha', '=', 'gen_carga_maxima.gen_cm_fecha')
        //->select('*')
        ->whereMonth('gen_cm_fecha', $month)
        ->whereYear('gen_cm_fecha', $year)
        //->orderBy('gen_maquina_gen_ma_id', 'ASC')
        ->get();

        echo "cmm: " . $cmm; // Salida: "Mes: 02"


        
        /* $resul_rep_fecha = DB::table('gen_rep_diario as a')
            ->join('gen_maquina as c', 'a.gen_maquina_gen_ma_id', '=', 'c.gen_ma_id')
            ->join('gen_planta as b', 'c.gen_planta_gen_pl_id', '=', 'b.gen_pl_id')
            ->select('a.*', 'b.gen_pl_nombre', 'c.gen_ma_nombre')
            ->where('a.gen_repd_fecha',  $fecha)
            ->where('b.gen_pl_id', $planta) */


        $resul_rep_mensual_b =  Reporte_Diario::select(
            DB::raw('SUM(gen_repd_carga_max_c) as gen_repd_carga_max_c'),
            DB::raw('SUM(gen_repd_gen_bruta) as gen_repd_gen_bruta'),
            DB::raw('SUM(gen_repd_cons_propio) as gen_repd_cons_propio'),
            DB::raw('SUM(gen_repd_gen_neta) as gen_repd_gen_neta'),
            DB::raw('SUM(gen_repd_cap_nominal) as gen_repd_cap_nominal'),
            DB::raw('SUM(gen_repd_cap_efectiva) as gen_repd_cap_efectiva'),
            DB::raw('SUM(gen_repd_carg_promedio) as gen_repd_carg_promedio'),
            DB::raw('SUM(gen_repd_indice_carg_promed_nominal) as gen_repd_indice_carg_promed_nominal'),
            DB::raw('SUM(gen_repd_hrs_operacion) as gen_repd_hrs_operacion'),
            DB::raw('SUM(gen_repd_hrs_disponibilidad) as gen_repd_hrs_disponibilidad'),
            DB::raw('SUM(gen_repd_disp_generador) as gen_repd_disp_generador'),
            DB::raw('SUM(gen_repd_cons_combustible_lts) as gen_repd_cons_combustible_lts'),
            DB::raw('SUM(gen_repd_cons_combustible_gal) as gen_repd_cons_combustible_gal'),
            DB::raw('SUM(gen_repd_efi_comb_bruta) as gen_repd_efi_comb_bruta'),
            DB::raw('SUM(gen_repd_efi_comb_neta) as gen_repd_efi_comb_neta'),
            DB::raw('SUM(gen_repd_con_comb_esp_bruto) as gen_repd_con_comb_esp_bruto'),
            DB::raw('SUM(gen_repd_cons_comb_esp_neto) as gen_repd_cons_comb_esp_neto'),
            DB::raw('SUM(gen_repd_cons_aceite_gal) as gen_repd_cons_aceite_gal'),
            DB::raw('SUM(gen_repd_cons_aceite_lts) as gen_repd_cons_aceite_lts'),
            DB::raw('SUM(gen_repd_hrs_trab_motor_tc) as gen_repd_hrs_trab_motor_tc'),
            DB::raw('SUM(gen_repd_hrs_last_overhaul) as gen_repd_hrs_last_overhaul'),
            DB::raw('SUM(gen_repd_hrs_last_mantenimiento) as gen_repd_hrs_last_mantenimiento'),
            DB::raw('SUM(gen_repd_hrs_trab_ace_lub_motor) as gen_repd_hrs_trab_ace_lub_motor'),
            'gen_maquina.gen_ma_id',
            'gen_maquina.gen_ma_nombre',
            DB::raw('MONTH(gen_repd_fecha) as mes'),
            DB::raw('YEAR(gen_repd_fecha) as año')
            
        )
        ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
        ->join('gen_planta', 'gen_pl_id', '=', 'gen_maquina.gen_planta_gen_pl_id')
        ->where('gen_planta.gen_pl_id', operator: $planta)
        ->whereMonth('gen_repd_fecha', $month)
        ->whereYear('gen_repd_fecha', $year)
        

        ->groupBy('gen_maquina.gen_ma_id', 'año', 'mes','gen_maquina.gen_ma_nombre','gen_planta.gen_pl_id')
        ->get();




        /*$resul_rep_mensual_b=  Reporte_Diario::select(
            DB::raw('SUM(gen_repd_carga_max_c) as gen_repd_carga_max_c'),
            DB::raw('SUM(gen_repd_gen_bruta) as gen_repd_gen_bruta'),
            DB::raw('SUM(gen_repd_cons_propio) as gen_repd_cons_propio'),
            DB::raw('SUM(gen_repd_gen_neta) as gen_repd_gen_neta'),
            DB::raw('SUM(gen_repd_cap_nominal) as gen_repd_cap_nominal'),
            DB::raw('SUM(gen_repd_cap_efectiva) as gen_repd_cap_efectiva'),
            DB::raw('SUM(gen_repd_carg_promedio) as gen_repd_carg_promedio'),
            DB::raw('SUM(gen_repd_indice_carg_promed_nominal) as gen_repd_indice_carg_promed_nominal'),
            DB::raw('SUM(gen_repd_hrs_operacion) as gen_repd_hrs_operacion'),
            DB::raw('SUM(gen_repd_hrs_disponibilidad) as gen_repd_hrs_disponibilidad'),
            DB::raw('SUM(gen_repd_disp_generador) as gen_repd_disp_generador'),
            DB::raw('SUM(gen_repd_cons_combustible_lts) as gen_repd_cons_combustible_lts'),
            DB::raw('SUM(gen_repd_cons_combustible_gal) as gen_repd_cons_combustible_gal'),
            DB::raw('SUM(gen_repd_efi_comb_bruta) as gen_repd_efi_comb_bruta'),
            DB::raw('SUM(gen_repd_efi_comb_neta) as gen_repd_efi_comb_neta'),
            DB::raw('SUM(gen_repd_con_comb_esp_bruto) as gen_repd_con_comb_esp_bruto'),
            DB::raw('SUM(gen_repd_cons_comb_esp_neto) as gen_repd_cons_comb_esp_neto'),
            DB::raw('SUM(gen_repd_cons_aceite_gal) as gen_repd_cons_aceite_gal'),
            DB::raw('SUM(gen_repd_cons_aceite_lts) as gen_repd_cons_aceite_lts'),
            DB::raw('SUM(gen_repd_hrs_trab_motor_tc) as gen_repd_hrs_trab_motor_tc'),
            DB::raw('SUM(gen_repd_hrs_last_overhaul) as gen_repd_hrs_last_overhaul'),
            DB::raw('SUM(gen_repd_hrs_last_mantenimiento) as gen_repd_hrs_last_mantenimiento'),
            DB::raw('SUM(gen_repd_hrs_trab_ace_lub_motor) as gen_repd_hrs_trab_ace_lub_motor'),
            'gen_maquina.gen_ma_id',
            'gen_maquina.gen_ma_nombre',
            DB::raw('MONTH(gen_repd_fecha) as mes'),
            DB::raw('YEAR(gen_repd_fecha) as año')
        )
        ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
        ->whereMonth('gen_repd_fecha', $month)
        ->whereYear('gen_repd_fecha', $year)
        ->groupBy('gen_maquina.gen_ma_id', 'año', 'mes','gen_maquina.gen_ma_nombre',)
        ->get();*/

        //return view('reportes.rep_mensual')->with('resul_rep_mensual_b', $resul_rep_mensual_b);        
       // dd(compact('cmm','resul_rep_mensual_b','planta'));
        //return view('reportes.rep_search_mensual', compact('cmm','resul_rep_mensual_b','planta','plantas'));

           if ($planta == 1) {
            return view('reportes.rep_search_mensual', compact('cmm','resul_rep_mensual_b','planta','plantas'));
            //     return view('reportes.rep_diario')->with('resul_rep_fecha', $resul_rep_fecha)->with('cm', $cm);
            } else { 
                return view('reportes.rep_search_mensual_emd', compact('cmm','resul_rep_mensual_b','planta','plantas'));
              //  return view('reportes.rep_diario_emd')->with('resul_rep_fecha', $resul_rep_fecha)->with('cm', $cm);
            }
        

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
}
