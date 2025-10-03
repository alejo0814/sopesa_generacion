<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\generacion_diaria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Models\Planta;
use App\Models\Generador;

use App\Models\Registro_hrs_trabajadas;
use App\Models\Registro_aceite;
use App\Models\Registro_combustible;
use Symfony\Contracts\Service\Attribute\Required;

class GeneracionDiariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recuperar todas las plantas generadoras
        $plantas = Planta::all();
        // Recuperar todos los registros de la tabla generacion_diaria
        $generaciones_diarias = generacion_diaria::all();
        // Pasar los datos a la vista
        return view('gen_diaria.crud', compact('generaciones_diarias', 'plantas'));
    }

    public function getGeneradores($planta_id)
    {
        $generadores = Generador::where('gen_planta_gen_pl_id', $planta_id)->get();
        return response()->json($generadores);
    }


    public function saveData(Request $request)
    {
        Log::info('Request method: ' . $request->method());
        Log::info('Request data: ', $request->all());

        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data = $request->validate([
                'fecha' => 'required|date', // Cambiar a string para pruebas
                'generador_id' => 'required|string',
                'planta_id' => 'required|string',
                'horas_trabajada' => 'required|numeric',
                'horas_disponible' => 'required|numeric',
                'lectura_kw' => 'required|numeric',
                'lectura_combustible' => 'required|numeric',
                'lectura_aceite' => 'required|numeric',
            ], [
                'fecha.required' => 'La fecha es obligatoria.',
                'fecha.date' => 'La fecha debe ser un formato valido.',
                'generador_id.required' => 'El generador es obligatorio.',
                'generador_id.string' => 'El generador debe ser un texto valido.',
                'planta_id.required' => 'La planta es obligatoria.',
                'planta_id.string' => 'La planta debe ser un texto valido.',
                'horas_trabajada.required' => 'Las horas trabajadas son obligatorias.',
                'horas_trabajada.numeric' => 'Las horas trabajadas deben ser un número.',
                'horas_disponible.required' => 'Las horas disponibles son obligatorias.',
                'horas_disponible.numeric' => 'Las horas disponibles deben ser un número.',
                'lectura_kw.required' => 'La lectura de kW es obligatoria.',
                'lectura_kw.numeric' => 'La lectura de kW debe ser un número.',
                'lectura_combustible.required' => 'La lectura de combustible es obligatoria.',
                'lectura_combustible.numeric' => 'La lectura de combustible debe ser un número.',
                'lectura_aceite.required' => 'La lectura de aceite es obligatoria.',
                'lectura_aceite.numeric' => 'La lectura de aceite debe ser un número.',
            ]);

            // Verificar si ya existe un registro para la fecha dada
            $existingRecord_hrs_trab = Registro_hrs_trabajadas::where('gen_reh_fecha', $data['fecha'])
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($existingRecord_hrs_trab) {
                Log::info(message: 'Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro para esta fecha y generador.'], 422);
            }
            /* if ($existingRecord) {

                Log::info('Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro para esta fecha.'], 422);
            }
 */
            // Guardar los datos en la base de datos
            $record = generacion_diaria::create($data);
            Log::info('Record saved: ', context: $record->toArray());

            // Calcular la fecha anterior
            $fechaAnterior = (new \DateTime($data['fecha']))->modify('-1 day')->format('Y-m-d');
            // error de la fecha anterior
           
            // Obtener las horas trabajadas del día anterior
            $hrs_t_anterior = Registro_hrs_trabajadas::where('gen_reh_fecha', $fechaAnterior)
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
             if ($fechaAnterior) {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No existe un registro el dia anterior.'], 422);
            }
            // $horasTrabajadasAnterior = $hrs_t_anterior ? $hrs_t_anterior->gen_reh_hora_trabajada : null;


            $acum = $hrs_t_anterior['gen_reh_hora_acum'] + $data['horas_trabajada'];
            // Guardar los datos en la tabla registro_horas
            $hrst = Registro_hrs_trabajadas::create([
                /*  'gen_reh_id', null o autoincrement*/
                /*  'gen_reh_id' => $data['generador_id'], */
                'gen_reh_fecha' => $data['fecha'],
                'gen_reh_hora_trabajada' => $data['horas_trabajada'],
                'gen_reh_hora_disponible' => $data['horas_disponible'],
                'gen_reh_hora_actuales' => $data['horas_trabajada'],
                'gen_reh_hora_acum' => $acum,
                'gen_registro_hora_ult_rep' => $data['horas_trabajada'],
                'gen_maquina_gen_ma_id' => $data['generador_id'],
                /*  'created_at',
                'updated_at' */

            ]);
            Log::info('Record saved horas: ', context: $hrst->toArray());


            //guardar los datos del combustible-------------------------------------------------------------------

            $existingRecord_hrs_trab = Registro_combustible::where('gen_rec_fecha', $data['fecha'])
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($existingRecord_hrs_trab) {
                Log::info('Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro de combustible para esta fecha y generador.'], 422);
            }

            // Guardar los datos en la base de datos
            //$record = generacion_diaria::create($data);
            //Log::info('Record saved: ', context: $record->toArray());

            // Calcular la fecha anterior
            //$fechaAnterior_comb = (new \DateTime($data['fecha']))->modify('-1 day')->format//('Y-m-d');
            // Obtener las horas trabajadas del día anterior
            $lect_combu_anterior = Registro_combustible::where('gen_rec_fecha', $fechaAnterior)
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();

            $acum = $hrs_t_anterior['gen_reh_hora_acum'] + $data['horas_trabajada'];
            // Guardar los datos en la tabla registro_horas
            $hrst = Registro_hrs_trabajadas::create([
                /*  'gen_reh_id', null o autoincrement*/
                /*  'gen_reh_id' => $data['generador_id'], */
                'gen_reh_fecha' => $data['fecha'],
                'gen_reh_hora_trabajada' => $data['horas_trabajada'],
                'gen_reh_hora_disponible' => $data['horas_disponible'],
                'gen_reh_hora_actuales' => $data['horas_trabajada'],
                'gen_reh_hora_acum' => $acum,
                'gen_registro_hora_ult_rep' => $data['horas_trabajada'],
                'gen_maquina_gen_ma_id' => $data['generador_id'],
            ]);
            Log::info('Record saved aceite: ', context: $hrst->toArray());















            // Guardar los datos en la tabla lectura_aceite
            //Registro_aceite::create([
            //    'fecha' => $data['fecha'],
            //    'lectura_aceite' => $data['lectura_aceite'],

            //    /* 'gen_rea_id', null o autoincrement*/
            //    'gen_rea_fecha' => $data['fecha'],
            //    'gen_rea_lectura',
            //    'gen_maquina_gen_ma_id'
            //]);

            // Guardar los datos en la tabla lectura_combustible
            // Registro_combustible::create([
            //     'fecha' => $data['fecha'],
            //     'lectura_combustible' => $data['lectura_combustible'],
            //
            //
            //
            //
            //    /*  'gen_rec_id', null o autoincrement*/
            //     'gen_rec_fecha' => $data['fecha'],
            //     'gen_rec_lectura_',
            //     'gen_maquina_gen_ma_id',
            // ]);

            // Confirmar la transacción
            DB::commit();

            Log::info('Datos de ingreso diario guardados correctamente en todas las tablas.');


            return response()->json(['success' => 'Datos guardados correctamente', 'data' => $data]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            return response()->json(['error' => 'Error de validación', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar los datos'], 500);
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
