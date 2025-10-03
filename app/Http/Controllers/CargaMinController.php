<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Ciudad;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Carga_min;


class CargaMinController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        // Log::info('Request method: ' . $request->method());
        // Log::info('Request data: ', $request->all());


        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data = $request->validate([
                'fecha' => 'required|date', // Cambiar a string para pruebas
                'ciudad' => 'required|numeric',
                'hora' => 'required|string',
                'carga_max' => 'required|numeric',

            ], [
                'ciudad.required' => 'Seleccione una sede este campo es obligatorio.',
                'fecha.required' => 'La fecha es obligatoria.',
                'fecha.date' => 'La fecha debe ser un formato valido.',
                'carga_max.required' => 'Los valores de carga maxima son obligatorias.',
                'carga_max.numeric' => 'Los valores de carga maxima deben ser númerico.',

            ]);

            // Verificar si ya existe un registro para la fecha dada
            $existingRecord_hrs_trab = Carga_min::where('gen_cmi_fecha', $data['fecha'])
                ->where('gen_ciudad_gen_ci_id', $data['ciudad'])
                ->first();
            if ($existingRecord_hrs_trab) {
                //Log::info(message: 'Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro para esta fecha y sede.'], 422);
            }

            $fechaAnterior = (new \DateTime($data['fecha']))->modify('-1 day')->format('Y-m-d');
            // error de la fecha anterior

            // Obtener las horas trabajadas del día anterior
            $hrs_t_anterior = Carga_min::where('gen_cmi_fecha', $fechaAnterior)
                ->where('gen_ciudad_gen_ci_id', $data['ciudad'])
                ->first();
            if ($hrs_t_anterior) {
                // Log::info('ok validacion dia anterior');
            } else {
                //Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            $carga_max_save = Carga_min::create([
                'gen_cmi_fecha' => $data['fecha'],
                'gen_cmi_value' => $data['carga_max'],
                'gen_cmi_hora' => $data['hora'],
                'gen_ciudad_gen_ci_id' => $data['ciudad'],
            ]);
            Log::info('Record saved carga maxima: ', context: $carga_max_save->toArray());
            DB::commit();

            return response()->json(['success' => 'Datos guardados correctamente.'], 200);

            // Guardar los datos en la base de datos
            // $record = Registro_carga_max::create($data);
            // Log::info('Record saved: ', context: $record->toArray());


        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            return response()->json(['error' => 'Error de validación ', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar los datos'], 500);
        }
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
