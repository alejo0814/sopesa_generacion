<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Reporte_Diario;
use App\Models\Registro_hrs_trabajadas;
use App\Models\Registro_aceite;
use App\Models\Registro_combustible;
use App\Models\Planta;

class GraficasGeneralesMesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function cons_mes(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->route('dash')->with('error', 'Método no permitido.');
        }

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
            $plantas = Planta::all();

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
            /*  return view('dash.graf_generales_mes')->with($e->errors())->with($plantas); */
            return view('dash.graf_error')->withErrors($e->errors())->with('plantas', $plantas = Planta::all());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            return view('dash.graf_error')->with('error', 'Ocurrió un error al procesar la solicitud.');
        }
    }

    /**    /**
     * Show the form for creating a new resource. the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.d resource in storage.
     */
    public function store(Request $request)
    {
        //        //
    }

    /**    /**
     * Display the specified resource.     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.iting the specified resource.
     */
    public function edit(string $id)
    {
        //   //
    }

    /**
     * Update the specified resource in storage.Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //   //
    }
}
