<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Ciudad;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Registro_carga_max;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CargaMaxController extends Controller
{
    // app/Http/Controllers/MiControlador.php

 public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $ciudades = Ciudad::all();




        // Datos que se colocan en la tabla para poder actualizar información
        $table_gen_cm = DB::select("SELECT a.gen_cm_id, a.gen_cm_fecha,a.gen_cm_value, a.gen_ciudad_gen_ci_id, b.gen_ci_nombre FROM gen_carga_maxima as a, gen_ciudad as b where a.gen_ciudad_gen_ci_id = b.gen_ci_id;");

        // Recuperar todas las plantas generadoras
       // $plantas = Planta::all();
        // Recuperar todos los registros de la tabla generacion_diaria
       // $generaciones_diarias = generacion_diaria::all();
        // Pasar los datos a la vista
       // return view('gen_diaria.crud')->with('as', $as)->with('plantas', $plantas);
        // return view('carpeta.mi_vista', compact('ciudades'));
         $ciudades=  ciudad::all();
        return view('carga_maxima.crud')->with('ciudades', $ciudades)->with('table_gen_cm', $table_gen_cm);
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
            $existingRecord_hrs_trab = Registro_carga_max::where('gen_cm_fecha', $data['fecha'])
                ->where('gen_ciudad_gen_ci_id', $data['ciudad'])
                ->first();
            if ($existingRecord_hrs_trab) {
                //Log::info(message: 'Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro para esta fecha y sede.'], 422);
            }

            $fechaAnterior = (new \DateTime($data['fecha']))->modify('-1 day')->format('Y-m-d');
            // error de la fecha anterior

            // Obtener las horas trabajadas del día anterior
            $hrs_t_anterior = Registro_carga_max::where('gen_cm_fecha', $fechaAnterior)
                ->where('gen_ciudad_gen_ci_id', $data['ciudad'])
                ->first();
            if ($hrs_t_anterior) {
                // Log::info('ok validacion dia anterior');
            } else {
                //Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            $carga_max_save = Registro_carga_max::create([
                'gen_cm_fecha' => $data['fecha'],
                'gen_cm_value' => $data['carga_max'],
                'gen_cm_hora' => $data['hora'],
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
        Log::info('dato que trae el id: ' . $id);
        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data = $request->validate([
                //'fecha' => 'required|date', // Cambiar a string para pruebas
                //'ciudad' => 'required|numeric',
                'cm_valor' => 'required|numeric',

            ], [
                //'ciudad.required' => 'Seleccione una sede este campo es obligatorio.',
                //'fecha.required' => 'La fecha es obligatoria.',
                //'fecha.date' => 'La fecha debe ser un formato valido.',
                'cm_valor.required' => 'Los valores de carga maxima son obligatorias.',
                'cm_valor.numeric' => 'Los valores de carga maxima deben ser númerico.',

            ]);



            DB::table('gen_carga_maxima')
                ->where('gen_cm_id', $id)
                ->update([
                    'gen_cm_value' => $data['cm_valor'],
                    'updated_at' => now() // Asegúrate de actualizar la columna de timestamp si existe
                ]);
          //
            // Verificar si ya existe un registro para la fecha dada
           // $existingRecord_hrs_trab = Registro_carga_max::where('gen_cm_fecha', $data['fecha'])
           //     ->where('gen_ciudad_gen_ci_id', $data['ciudad'])
           //     ->first();
           // if ($existingRecord_hrs_trab) {
           //     //Log::info(message: 'Ya existe un registro');
           //     return response()->json(['error' => 'Ya existe un registro para esta fecha y sede.'], 422);
           // }
//
           // $fechaAnterior = (new \DateTime($data['fecha']))->modify('-1 day')->format('Y-m-d');
           // // error de la fecha anterior
//
           // // Obtener las horas trabajadas del día anterior
           // $hrs_t_anterior = Registro_carga_max::where('gen_cm_fecha', $fechaAnterior)
           //     ->where('gen_ciudad_gen_ci_id', $data['ciudad'])
           //     ->first();
           // if ($hrs_t_anterior) {
           //     // Log::info('ok validacion dia anterior');
           // } else {
           //     //Log::info('no se encuentran datos del dia anterior');
           //     return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], //422);
           // }
//
           // $carga_max_save = Registro_carga_max::create([
           //     'gen_cm_fecha' => $data['fecha'],
           //     'gen_cm_value' => $data['carga_max'],
           //     'gen_ciudad_gen_ci_id' => $data['ciudad'],
           // ]);
           // Log::info('Record saved carga maxima: ', context: $carga_max_save->toArray());
            DB::commit();

            return response()->json(['success' => 'Datos actualizados correctamente.'], 200);

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function delete(Request $request, $id)
    {
       
    try {
        DB::beginTransaction();
        Registro_carga_max::where('gen_cm_id', $id)->delete();
        DB::commit();
        Log::info('All records deleted successfully : ');

        return response()->json(['success' => 'Registros eliminados correctamente.'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error deleting records: ' . $e->getMessage());
        return response()->json(['error' => 'Error al eliminar los registros.'], 500);
    }
    }
}
