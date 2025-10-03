<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Reporte_Diario;
use App\Models\Registro_hrs_trabajadas;
use App\Models\Registro_aceite;
use App\Models\Registro_combustible;
use App\Models\Planta;
use App\Models\Control_Carga;
use App\Models\Registro_carga_max;
use App\Models\Carga_min;


class GraficasControlCarga extends Controller
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

        /* 
        posibles consultas 

        SELECT * FROM `gen_control_carga` WHERE `gen_cg_fecha`='2025-05-01';
        SELECT * FROM `gen_control_carga` WHERE `gen_cg_fecha` BETWEEN '2025-05-01' and '2025-05-30';




        consulta para el grafico de carga diaria donde escoge la carga maxima 
        SELECT `gen_cg_id`, `gen_cg_fecha`, `gen_cg_hora`, sum(`gen_cg_valor_kw`), `gen_maquina_gen_ma_id`, `created_at`, `updated_at` FROM `gen_control_carga` WHERE gen_cg_fecha='2025-05-19' GROUP by gen_cg_hora ORDER BY `gen_control_carga`.`gen_cg_id` ASC;


        */



        //$startOfLastMonth = Carbon::now()->startOfMonth();
        //$endOfLastMonth = Carbon::now()->endOfMonth();
        /* 
SELECT 'gen_cg_id', 'gen_cg_fecha', 'gen_cg_hora', sum('gen_cg_valor_kw'), 'gen_maquina_gen_ma_id', 'created_at', 'updated_at' FROM `gen_control_carga` WHERE gen_cg_fecha='2025-05-19' GROUP by gen_cg_hora ORDER BY `gen_control_carga`.`gen_cg_id` ASC;
 */
        $fechaHoy = Carbon::now()->toDateString();
        $starttMonth = Carbon::now()->startOfMonth()->toDateString();
        $endMonth = Carbon::now()->endOfMonth()->toDateString();

        $control_carga_dia = Control_Carga::selectRaw('gen_cg_hora, SUM(gen_cg_valor_kw) as total_kw')
            // ->where('gen_cg_fecha', $fechaHoy)
            ->where('gen_cg_fecha', '2025-05-23')
            ->groupBy('gen_cg_hora')
            ->orderBy('gen_cg_id', 'asc')
            ->get();

        $carga_dia_data = $control_carga_dia->pluck('total_kw');
        $carga_diaria_hora = $control_carga_dia->pluck('gen_cg_hora');

        //$control_carga_dia = Control_Carga::where('gen_cg_fecha', '=', $fechaHoy)
        //  
        //    ->select('gen_cg_id', 'gen_cg_fecha', 'gen_cg_hora', 'gen_cg_valor_kw')
        //     ->sum('gen_cg_valor_kw') // Usar sum para obtener el valor numérico */
        //    ->get();

        $carga_min_mes = Carga_min::where('gen_ciudad_gen_ci_id', '=', 1)
            ->whereBetween('gen_cmi_fecha', [$starttMonth, $endMonth])
            ->select('gen_cmi_fecha', 'gen_cmi_value', 'gen_cmi_hora')
            ->get();


        $carga_min_fech = $carga_min_mes->pluck('gen_cmi_fecha');
        $carga_min_val = $carga_min_mes->pluck('gen_cmi_value');
        $carga_min_hora = $carga_min_mes->pluck('gen_cmi_hora');


        $carga_max_mes = Registro_carga_max::where('gen_ciudad_gen_ci_id', '=', 1)
            ->whereBetween('gen_cm_fecha', [$starttMonth, $endMonth])
            ->select('gen_cm_fecha', 'gen_cm_value', 'gen_cm_hora')
            ->get();


        $carga_max_fech = $carga_max_mes->pluck('gen_cm_hora');
        $carga_max_val = $carga_max_mes->pluck('gen_cm_value');
        $carga_max_hora = $carga_max_mes->pluck('gen_cm_hora');




        //   $hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 1)
        //       ->select('gen_reh_hora_acum')
        //       ->orderBy('gen_reh_fecha', 'desc')
        //       ->first();
        //
        //   $hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', '=', 2)
        //       ->select('gen_reh_hora_acum')
        //       ->orderBy('gen_reh_fecha', 'desc')
        //       ->first();
        //

        // Prepara los datos para Chart.js
        // $labels1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
        // $values1 = $datos_ultimo_mes1->pluck('gen_repd_hrs_disponibilidad');
        //  $labels2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
        //  $values2 = $datos_ultimo_mes2->pluck('gen_repd_hrs_disponibilidad');

        // Obtener los datos de horas acumuladas para MAN 1
        /*  $datos_hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', operator: 1)
            ->sum('gen_reh_hora_acum'); // Usar sum para obtener el valor numérico */
        //  $datos_hrs_acum_1 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 1)
        //      ->orderBy('gen_reh_fecha', 'desc')
        //      ->first();

        //  // Obtener los datos de horas acumuladas para MAN 2
        //  /* $datos_hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 2)
        //      ->sum('gen_reh_hora_acum'); // Usar sum para obtener el valor numérico */
        //  $datos_hrs_acum_2 = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', 2)
        //      ->orderBy('gen_reh_fecha', 'desc')
        //      ->first();
        //  // Calcular el porcentaje
        //  // $hrs_acum_porce_1 = ($datos_hrs_acum_1 / 1000) * 100;
        //  // $hrs_acum_porce_2 = ($datos_hrs_acum_2 / 1000) * 100;
        //  // Verificar si se encontraron registros antes de acceder a los atributos
        //  $hrs_acum_porce_1 = $datos_hrs_acum_1 ? ($datos_hrs_acum_1->gen_registro_hora_ult_rep / 18000) * 100 : 0;
        //  $hrs_acum_porce_2 = $datos_hrs_acum_2 ? ($datos_hrs_acum_2->gen_registro_hora_ult_rep / 18000) * 100 : 0;

        //  $hrs_acum1 = $datos_hrs_acum_1->gen_registro_hora_ult_rep;
        //  //$hrs_acum_porce_1 = ($datos_hrs_acum_1 / 1000) * 100;
        //  $hrs_acum2 = $datos_hrs_acum_2->gen_registro_hora_ult_rep;
        //  //$hrs_acum_porce_2 = ($datos_hrs_acum_2 / 1000) * 100;





        //datos para el combustible

        // $datos_comb_acum_1 = Registro_combustible::where('gen_maquina_gen_ma_id', 1)
        //     ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
        //     ->orderBy('gen_rec_fecha', 'asc');
        // //->first(['gen_rec_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);

        // $datos_comb_acum_2 = Registro_combustible::where('gen_maquina_gen_ma_id', 2)
        //     ->whereBetween('gen_rec_fecha', [$startOfLastMonth, $endOfLastMonth])
        //     ->orderBy('gen_rec_fecha', 'asc');
        // // ->first(['gen_rec_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']arga_dia_dataomb_acum1 = $datos_comb_acum_1->pluck('gcarga_diaria_horasumno');
        // $fech_comb1 = $datos_comb_acum_1->pluck('gen_rec_fecha');

        // $comb_acum2 = $datos_comb_acum_2->pluck('gen_rec_consumno');
        // $fech_comb2 = $datos_comb_acum_2->pluck('gen_rec_fecha');

        //datos combustible litros
        //$fecha_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
        //$comb_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_cons_combustible_lts');
        //$fecha_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
        //$comb_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_cons_combustible_lts');








        //datos para el aceite
        //
        // $datos_aceite_acum_1 = Registro_aceite::where('gen_maquina_gen_ma_id', 1)
        //     ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
        //     ->orderBy('gen_rea_fecha', 'asc');
        // //->first(['gen_rea_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);
        //
        // $datos_aceite_acum_2 = Registro_aceite::where('gen_maquina_gen_ma_id', 2)
        //     ->whereBetween('gen_rea_fecha', [$startOfLastMonth, $endOfLastMonth])
        //     ->orderBy('gen_rea_fecha', 'asc');
        // // ->first(['gen_rea_fecha', 'gen_maquina_gen_ma_id', 'gen_rec_consumno']);


        // $aceite_acum1 = $datos_aceite_acum_1->pluck('gen_rea_consumo');
        // $fech_aceite1 = $datos_aceite_acum_1->pluck('gen_rea_fecha');
        //
        // $aceite_acum2 = $datos_aceite_acum_2->pluck('gen_rea_consumo');
        // $fech_aceite2 = $datos_aceite_acum_2->pluck('gen_rea_fecha');

        //datos combustible litros
        //$fecha_lts_aceite_1 = $datos_ultimo_mes1->pluck('gen_repd_fecha');
        //$aceite_lts_1 = $datos_ultimo_mes1->pluck('gen_repd_cons_combustible_lts');
        //$fecha_lts_aceite_2 = $datos_ultimo_mes2->pluck('gen_repd_fecha');
        //$aceite_lts_2 = $datos_ultimo_mes2->pluck('gen_repd_cons_combustible_lts');


        //dd(compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2','hrs_acum1','hrs_acum1', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2','fecha_lts_1','comb_lts_1','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2'));
        //dd(compact('starttMonth','endMonth','carga_max_val','carga_max_fech','carga_min_val','carga_min_fech'));

        // dd(compact('carga_max_hora','carga_min_hora'));

        return view('dash.graf_control_carga', compact('carga_max_val', 'carga_max_fech', 'carga_min_val', 'carga_min_fech', 'control_carga_dia', 'carga_diaria_hora', 'carga_dia_data', 'plantas', 'fechaHoy', 'carga_max_hora', 'carga_min_hora'));

        // return view('dash.graf_generales', compact('control_carga_dia','carga_diaria_hora','carga_dia_data',  'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2','hrs_acum1','hrs_acum2', 'hrs_acum_porce_2',  'comb_acum2', 'fech_comb1', 'fech_comb2','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2','plantas'));
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


    //  public function buscar_c_c_graf_dia(Request $request)
    //  {
    //      if (!$request->isMethod('post')) {
    //          return redirect()->route('dash')->with('error', 'Método no permitido.');
    //      }
    //
    //      try {
    //
    //           $data = $request->validate([
    //              'fech_buscar_c_c_d' => 'required|date', // Cambiar a string para pruebas
    //              // 'planta_id' => 'required|string',
    //          ], [
    //              'fech_buscar_c_c_d.required' => 'La fecha es obligatoria.',
    //              'fech_buscar_c_c_d.date' => 'La fecha debe ser un formato valido.',
    //
    //              //'planta_id.required' => 'La planta es obligatoria.',
    //              //'planta_id.string' => 'La planta debe ser un texto valido.',
    //
    //          ]);
    //          //$plantas = Planta::all();
    //
    //          // Lógica para manejar la consulta
    //          $fech_buscar_c_c_d = $request->input('fech_buscar_c_c_d');
    //          $plantas = Planta::all();
    //          $fechaHoy = Carbon::now()->toDateString();
    //          $starttMonth = Carbon::now()->startOfMonth()->toDateString();
    //          $endMonth = Carbon::now()->endOfMonth()->toDateString();
    //
    //          $control_carga_dia = Control_Carga::selectRaw('gen_cg_hora, SUM(gen_cg_valor_kw) as total_kw')
    //              // ->where('gen_cg_fecha', $fechaHoy)
    //              ->where('gen_cg_fecha', $fech_buscar_c_c_d)
    //              ->groupBy('gen_cg_hora')
    //              ->orderBy('gen_cg_id', 'asc')
    //              ->get();
    //
    //          $carga_dia_data = $control_carga_dia->pluck('total_kw');
    //          $carga_diaria_hora = $control_carga_dia->pluck('gen_cg_hora');
    //
    //          $carga_min_mes = Carga_min::where('gen_ciudad_gen_ci_id', '=', 1)
    //              ->whereBetween('gen_cmi_fecha', [$starttMonth, $endMonth])
    //              ->select('gen_cmi_fecha', 'gen_cmi_value', 'gen_cmi_hora')
    //              ->get();
    //
    //          $carga_min_fech = $carga_min_mes->pluck('gen_cmi_fecha');
    //          $carga_min_val = $carga_min_mes->pluck('gen_cmi_value');
    //          $carga_min_hora = $carga_min_mes->pluck('gen_cmi_hora');
    //
    //
    //          $carga_max_mes = Registro_carga_max::where('gen_ciudad_gen_ci_id', '=', 1)
    //              ->whereBetween('gen_cm_fecha', [$starttMonth, $endMonth])
    //              ->select('gen_cm_fecha', 'gen_cm_value', 'gen_cm_hora')
    //              ->get();
    //
    //          $carga_max_fech = $carga_max_mes->pluck('gen_cm_hora');
    //          $carga_max_val = $carga_max_mes->pluck('gen_cm_value');
    //          $carga_max_hora = $carga_max_mes->pluck('gen_cm_hora');
    //          // dd(compact('carga_max_hora','carga_min_hora'));
    //          DB::commit();
    //          //dd(compact('carga_max_val',  'carga_diaria_hora', 'carga_dia_data', 'fechaHoy', 'fech_buscar_c_c_d'));
    //          return view('dash.graf_control_carga', compact('carga_max_val', 'plantas', 'carga_diaria_hora', 'carga_dia_data', 'fechaHoy', 'fech_buscar_c_c_d'));
    //          //return view('dash.graf_control_carga', compact('carga_max_val', 'carga_max_fech', 'carga_min_val', 'carga_min_fech', 'control_carga_dia', 'carga_diaria_hora', 'carga_dia_data', 'fechaHoy', 'carga_max_hora', 'carga_min_hora'));
    //
    //          // return view('dash.graf_generales', compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2', 'hrs_acum1', 'hrs_acum2', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2', 'fecha_lts_1', 'comb_lts_1', 'comb_lts_2', 'aceite_acum1', 'fech_aceite1', 'aceite_acum2', 'fech_aceite2', 'plantas'));
    //      } catch (ValidationException $e) {
    //          DB::rollBack();
    //          Log::error('Validation error: ', $e->errors());
    //          /*  return view('dash.graf_generales_mes')->with($e->errors())->with($plantas); */
    //          return view('dash.graf_error')->withErrors($e->errors())->with('plantas', $plantas = Planta::all());
    //      } catch (\Exception $e) {
    //          DB::rollBack();
    //          Log::error('General error: ' . $e->getMessage());
    //         /*  return view('dash.graf_error')->with('error', 'Ocurrió un error al procesar la solicitud.'); */
    //      }
    //  }

    public function buscar_c_c_graf_month()
    {
        if (!$request->isMethod('post')) {
            return redirect()->route('dash')->with('error', 'Método no permitido.');
        }

        try {
            $plantas = Planta::all();
            $fechaHoy = Carbon::now()->toDateString();
            $starttMonth = Carbon::now()->startOfMonth()->toDateString();
            $endMonth = Carbon::now()->endOfMonth()->toDateString();

            $control_carga_dia = Control_Carga::selectRaw('gen_cg_hora, SUM(gen_cg_valor_kw) as total_kw')
                // ->where('gen_cg_fecha', $fechaHoy)
                ->where('gen_cg_fecha', '2025-05-23')
                ->groupBy('gen_cg_hora')
                ->orderBy('gen_cg_id', 'asc')
                ->get();

            $carga_dia_data = $control_carga_dia->pluck('total_kw');
            $carga_diaria_hora = $control_carga_dia->pluck('gen_cg_hora');

            $carga_min_mes = Carga_min::where('gen_ciudad_gen_ci_id', '=', 1)
                ->whereBetween('gen_cmi_fecha', [$starttMonth, $endMonth])
                ->select('gen_cmi_fecha', 'gen_cmi_value', 'gen_cmi_hora')
                ->get();

            $carga_min_fech = $carga_min_mes->pluck('gen_cmi_fecha');
            $carga_min_val = $carga_min_mes->pluck('gen_cmi_value');
            $carga_min_hora = $carga_min_mes->pluck('gen_cmi_hora');


            $carga_max_mes = Registro_carga_max::where('gen_ciudad_gen_ci_id', '=', 1)
                ->whereBetween('gen_cm_fecha', [$starttMonth, $endMonth])
                ->select('gen_cm_fecha', 'gen_cm_value', 'gen_cm_hora')
                ->get();

            $carga_max_fech = $carga_max_mes->pluck('gen_cm_hora');
            $carga_max_val = $carga_max_mes->pluck('gen_cm_value');
            $carga_max_hora = $carga_max_mes->pluck('gen_cm_hora');
            // dd(compact('carga_max_hora','carga_min_hora'));

            return view('dash.graf_control_carga', compact('carga_max_val', 'carga_max_fech', 'carga_min_val', 'carga_min_fech', 'control_carga_dia', 'carga_diaria_hora', 'carga_dia_data', 'plantas', 'fechaHoy', 'carga_max_hora', 'carga_min_hora'));
            DB::commit();
            return view('dash.graf_generales', compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2', 'hrs_acum1', 'hrs_acum2', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2', 'fecha_lts_1', 'comb_lts_1', 'comb_lts_2', 'aceite_acum1', 'fech_aceite1', 'aceite_acum2', 'fech_aceite2', 'plantas'));
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            /*  return view('dash.graf_generales_mes')->with($e->errors())->with($plantas); */
            return view('dash.graf_error')->withErrors($e->errors())->with('plantas', $plantas = Planta::all());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            return view('dash.graf_error')->with('error', 'Ocurrió un error al procesar la solicitud.');
        }
    }






    public function buscar_c_c_graf_dia_ajax(Request $request)
    {
        try {
            $data = $request->validate([
                'fech_buscar_c_c_d' => 'required|date',
            ]);

            $fech_buscar_c_c_d = $request->input('fech_buscar_c_c_d');

            $control_carga_dia = Control_Carga::selectRaw('gen_cg_hora, SUM(gen_cg_valor_kw) as total_kw')
                ->where('gen_cg_fecha', $fech_buscar_c_c_d)
                ->groupBy('gen_cg_hora')
                ->orderBy('gen_cg_id', 'asc')
                ->get();

            return response()->json([
                'fechaHoy' => $fech_buscar_c_c_d,
                'labels' => $control_carga_dia->pluck('gen_cg_hora'),
                'data' => $control_carga_dia->pluck('total_kw'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }



    public function buscar_c_c_graf_mes_ajax(Request $request)
    {
        Log::info('Inicio de consulta AJAX para gráfica mensual');


        try {
            //$data = $request->validate([
            //    'fecha_buscar_c_c_m' => 'required|month_format:Y-m', // Validación del formato de mes
            //]);
            //Log::info('Validación exitosa del parámetro mes', ['fecha_buscar_c_c_m' => $data['fecha_buscar_c_c_m']]);

            $fecha_m = $request->input('fech_buscar_c_c_m');
            //$fecha_m = $request->input('fecha_buscar_c_c_m');
            Log::info('valor fecha', ['fecha_buscar_c_c_m' => $fecha_m]);

            list($year, $month) = explode('-', $fecha_m);
            //echo "Año: " . $year; // Salida: "Año: 2025"
            //echo "Mes: " . $month; // Salida: "Mes: 02"
            // $fecha = $request->input('fecha_buscar_c_c_m');
            // $mes = Carbon::createFromFormat('Y-m', $fecha)->startOfMonth()->toDateString();
            // $año = Carbon::createFromFormat('Y-m', $fecha)->endOfMonth()->toDateString();



            //$fechaInicio = Carbon::createFromFormat('Y-m', $data['mes'])->startOfMonth()->toDateString();
            //$fechaFin = Carbon::createFromFormat('Y-m', $data['mes'])->endOfMonth()->toDateString();
            Log::info('Rango de fechas calculado', ['año' => $year, 'mes' => $month]);


            // Lógica para manejar la consulta
            // $fech_buscar_c_c_d = $request->input('fech_buscar_c_c_d');
            //$plantas = Planta::all();
            //$fechaHoy = Carbon::now()->toDateString();
            //$starttMonth = Carbon::now()->startOfMonth()->toDateString();
            //$endMonth = Carbon::now()->endOfMonth()->toDateString();






            /*             $carga_min_mes = Carga_min::where('gen_ciudad_gen_ci_id', '=', 1)
                            ->whereMonth('gen_cmi_fecha', $month)
                            ->whereYear('gen_cmi_fecha', $year)
                            // ->whereBetween('gen_cmi_fecha', [$fechaInicio, $fechaFin])
                            ->select('gen_cmi_fecha', 'gen_cmi_value', 'gen_cmi_hora')
                            ->get();
                        Log::info('Consulta de carga mínima realizada', ['registros' => $carga_min_mes->count()]); */

            /*   $carga_max_mes = Registro_carga_max::where('gen_ciudad_gen_ci_id', '=', 1)
                //->whereBetween('gen_cm_fecha', [$fechaInicio, $fechaFin])
                ->whereMonth('gen_cm_fecha', $month)
                ->whereYear('gen_cm_fecha', $year)
                ->select('gen_cm_fecha', 'gen_cm_value', 'gen_cm_hora')
                ->get();
            Log::info('Consulta de carga máxima realizada', ['registros' => $carga_max_mes]);
 */

            /*  $carga_max_por_dia = DB::table('registro_carga_max')
                ->select(DB::raw('DATE(gen_cm_fecha) as fecha'), DB::raw('MAX(gen_cm_value) as valor_maximo'))
                ->where('gen_ciudad_gen_ci_id', 1)
                ->whereMonth('gen_cm_fecha', $month)
                ->whereYear('gen_cm_fecha', $year)
                ->groupBy(DB::raw('DATE(gen_cm_fecha)'))
                ->orderBy('fecha')
                ->get();
            Log::info('Consulta de carga máxima por día realizada', ['registros' => $carga_max_por_dia]);


            $carga_max_por_dia = DB::table('registro_carga_max')
                ->select(DB::raw('DATE(gen_cm_fecha) as fecha'), DB::raw('MAX(gen_cm_value) as valor_maximo'))
                ->where('gen_ciudad_gen_ci_id', 1)
                ->whereMonth('gen_cm_fecha', $month)
                ->whereYear('gen_cm_fecha', $year)
                ->groupBy(DB::raw('DATE(gen_cm_fecha)'))
                ->orderBy('fecha')
                ->get();
            Log::info('Consulta de carga máxima por día realizada', ['registros' => $carga_max_por_dia]); */





            /* 
                        $carga_min_mes = DB::table('gen_carga_min')
                            ->select(DB::raw('DATE(gen_cmi_fecha) as fecha_min'), DB::raw('MIN(gen_cmi_value) as valor_minimo'))
                            ->where('gen_ciudad_gen_ci_id', 1)
                            ->whereMonth('gen_cmi_fecha', $month) // Enero
                            ->whereYear('gen_cmi_fecha', $year)
                            ->groupBy(DB::raw('DATE(gen_cmi_fecha)'))
                            ->orderBy('fecha_min')
                            ->get(); */



            /*             $carga_min_mes = DB::table('gen_carga_min')
                            ->select(DB::raw('DATE(gen_cmi_fecha) as fecha_min'), DB::raw('MIN(gen_cmi_value) as valor_minimo'), 'gen_cmi_hora as hora_min')
                            ->where('gen_ciudad_gen_ci_id', 1)
                            ->whereMonth('gen_cmi_fecha', $month)
                            ->whereYear('gen_cmi_fecha', $year)
                            ->groupBy(DB::raw('DATE(gen_cmi_fecha)'), 'gen_cmi_hora')
                            ->orderBy('fecha_min')
                            ->get();
             */
/* 
            $carga_min_mes = DB::table('gen_carga_min as main')
                ->select(DB::raw('DATE(main.gen_cmi_fecha) as fecha_min'), 'main.gen_cmi_value as valor_minimo', 'main.gen_cmi_hora as hora_min')
                ->where('main.gen_ciudad_gen_ci_id', 1)
                ->whereMonth('main.gen_cmi_fecha', $month)
                ->whereYear('main.gen_cmi_fecha', $year)
                ->whereRaw('main.gen_cmi_value = (SELECT MIN(sub.gen_cmi_value) FROM gen_carga_min as sub WHERE DATE(sub.gen_cmi_fecha) = DATE(main.gen_cmi_fecha) AND sub.gen_ciudad_gen_ci_id = 1 AND MONTH(sub.gen_cmi_fecha) = 1 AND YEAR(sub.gen_cmi_fecha) = 2025)')
                ->orderBy('fecha_min')
                ->get(); */

                
     $carga_min_mes = DB::table('gen_carga_min as main_min')
                ->select(DB::raw('DATE(main_min.gen_cmi_fecha) as fecha_min'), 'main_min.gen_cmi_value as valor_minimo','main_min.gen_cmi_hora as hora_min' )
                ->where('main_min.gen_ciudad_gen_ci_id', 1)
                ->whereMonth('main_min.gen_cmi_fecha', $month)
                ->whereYear('main_min.gen_cmi_fecha', $year)
                ->whereRaw("main_min.gen_cmi_value = (SELECT MIN(sub.gen_cmi_value) FROM gen_carga_min as sub WHERE DATE(sub.gen_cmi_fecha) = DATE(main_min.gen_cmi_fecha) AND sub.gen_ciudad_gen_ci_id = 1 AND MONTH(sub.gen_cmi_fecha) = ? AND YEAR(sub.gen_cmi_fecha) = ?)", [$month, $year])
                ->orderBy('fecha_min')
                ->get();


            Log::info('Consulta de carga minima por día realizada', ['registros' => $carga_min_mes]);

            /* 
                        $carga_max_mes = DB::table('gen_carga_maxima')
                            ->select(DB::raw('DATE(gen_cm_fecha) as fecha'), DB::raw('MAX(gen_cm_value) as valor_maximo'), DB::raw('MAX(gen_cm_hora) as hora_max'))
                            ->where('gen_ciudad_gen_ci_id', 1)
                            ->whereMonth('gen_cm_fecha', $month) // Enero
                            ->whereYear('gen_cm_fecha', $year)
                            ->groupBy(DB::raw('DATE(gen_cm_fecha)'))
                            ->orderBy('fecha')
                            ->get();

             */
            $carga_max_mes = DB::table('gen_carga_maxima as main_max')
                ->select(DB::raw('DATE(main_max.gen_cm_fecha) as fecha'), 'main_max.gen_cm_value as valor_maximo','main_max.gen_cm_hora as hora_max' )
                ->where('main_max.gen_ciudad_gen_ci_id', 1)
                ->whereMonth('main_max.gen_cm_fecha', $month)
                ->whereYear('main_max.gen_cm_fecha', $year)
                ->whereRaw("main_max.gen_cm_value = (SELECT MAX(sub.gen_cm_value) FROM gen_carga_maxima as sub WHERE DATE(sub.gen_cm_fecha) = DATE(main_max.gen_cm_fecha) AND sub.gen_ciudad_gen_ci_id = 1 AND MONTH(sub.gen_cm_fecha) = ? AND YEAR(sub.gen_cm_fecha) = ?)", [$month, $year])
                ->orderBy('fecha')
                ->get();

           /*  $carga_max_mes = DB::table('gen_carga_maxima as main_max')
                ->select(DB::raw('DATE(main_max.gen_cm_fecha) as fecha_min'), 'main_max.gen_cm_value as valor_minimo', 'main_max.gen_cm_hora as hora_min')
                ->where('main_max.gen_ciudad_gen_ci_id', 1)
                ->whereMonth('main_max.gen_cm_fecha', $month)
                ->whereYear('main_max.gen_cm_fecha', $year)
                ->whereRaw('main_max.gen_cm_value = (SELECT MIN(sub.gen_cm_value) FROM gen_carga_maxima as sub WHERE DATE(sub.gen_cm_fecha) = DATE(main_max.gen_cm_fecha) AND sub.gen_ciudad_gen_ci_id = 1 AND MONTH(sub.gen_cm_fecha) = 1 AND YEAR(sub.gen_cm_fecha) = 2025)')
                ->orderBy('fecha_min')
                ->get(); */


            Log::info('Consulta de carga máxima por día realizada', ['registros' => $carga_max_mes]);



            return response()->json([
                'min_fech' => $carga_min_mes->pluck('fecha_min'),
                'min_val' => $carga_min_mes->pluck('valor_minimo'),
                'min_hora' => $carga_min_mes->pluck('hora_min'),
                'max_fech' => $carga_max_mes->pluck('fecha'),
                'max_val' => $carga_max_mes->pluck('valor_maximo'),
                'max_hora' => $carga_max_mes->pluck('hora_max'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', ['errores' => $e->errors()]);
            return response()->json(['error' => 'Error de validación', 'detalles' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error general en la consulta mensual', ['mensaje' => $e->getMessage()]);
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }
}
