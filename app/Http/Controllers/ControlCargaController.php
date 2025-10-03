<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Ciudad;
use App\Models\Control_Carga;
use App\Models\Generador;

use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Registro_carga_max;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ControlCargaController extends Controller
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



        $ciudades = Ciudad::all();
        $generadores = Generador::all();

        $ciudades =  ciudad::all();

        $fechaHoy = Carbon::now()->toDateString();
        $datos_hoy = DB::select("SELECT * FROM `gen_control_carga` WHERE gen_cg_fecha='$fechaHoy' order by gen_cg_hora asc;");

        $table_gen_cm = DB::select("SELECT a.gen_cm_id, a.gen_cm_fecha,a.gen_cm_value, a.gen_ciudad_gen_ci_id, b.gen_ci_nombre FROM gen_carga_maxima as a, gen_ciudad as b where a.gen_ciudad_gen_ci_id = b.gen_ci_id;");

        $carga_max_hoy = DB::select("SELECT * FROM `gen_carga_maxima` WHERE gen_cm_fecha='$fechaHoy';");
        $carga_min_hoy = DB::select("SELECT * FROM `gen_carga_minima` WHERE gen_cmi_fecha='$fechaHoy';");


        //$datos_hoy = [
        // Tu arreglo de datos aquí
        //];

        // Organizar los datos por hora
        $tabla = [];
        foreach ($datos_hoy as $dato) {
            $hora = substr($dato->gen_cg_hora, 0, 2); // Obtener la hora en formato "HH"
            if (!isset($tabla[$hora])) {
                $tabla[$hora] = [
                    'hora' => "~",
                    'MB 01' => "~",
                    'MB 02' => "~",
                    'MAN 01' => "~",
                    'MAN 02' => "~",
                    'EMD 09' => "~",
                    'EMD 10' => "~",
                    'EMD 11' => "~",
                    'EMD 12' => "~",
                    'EMD 13' => "~",
                    'EMD 14' => "~",

                    'TOTAL (KW)' => 0,
                ];
            }



            if ($dato->gen_maquina_gen_ma_id == 1) {
                $tabla[$hora]['ID MAN 01'] = $dato->gen_cg_id;
                $tabla[$hora]['MAN 01'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 2) {
                $tabla[$hora]['ID MAN 02'] = $dato->gen_cg_id;
                $tabla[$hora]['MAN 02'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 3) {
                $tabla[$hora]['ID MB 01'] = $dato->gen_cg_id;
                $tabla[$hora]['MB 01'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 4) {
                $tabla[$hora]['ID MB 02'] = $dato->gen_cg_id;
                $tabla[$hora]['MB 02'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 5) {
                $tabla[$hora]['ID EMD 09'] = $dato->gen_cg_id;
                $tabla[$hora]['EMD 09'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 6) {
                $tabla[$hora]['ID EMD 10'] = $dato->gen_cg_id;
                $tabla[$hora]['EMD 10'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 7) {
                $tabla[$hora]['ID EMD 11'] = $dato->gen_cg_id;
                $tabla[$hora]['EMD 11'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 8) {
                $tabla[$hora]['ID EMD 12'] = $dato->gen_cg_id;
                $tabla[$hora]['EMD 12'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 9) {
                $tabla[$hora]['ID EMD 13'] = $dato->gen_cg_id;
                $tabla[$hora]['EMD 13'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            } elseif ($dato->gen_maquina_gen_ma_id == 10) {
                $tabla[$hora]['ID EMD 14'] = $dato->gen_cg_id;
                $tabla[$hora]['EMD 14'] = $dato->gen_cg_valor_kw;
                $tabla[$hora]['hora'] = $dato->gen_cg_hora;
            };


            // Asignar el valor correspondiente a la columna adecuada
            $tabla[$hora]['TOTAL (KW)'] += $dato->gen_cg_valor_kw;
            // $tabla[$hora]['TOTAL (KW)'] += $dato->gen_maquina_gen_ma_id;
            // Aquí puedes agregar lógica para asignar valores a las columnas específicas


            //  return view('tu_vista', compact('tabla'));
        }



        //dd(compact('tabla', 'datos_hoy'));



        return view('control_carga.crud')->with('ciudades', $ciudades)->with('datos_hoy', $datos_hoy)->with('tabla', $tabla)->with('generadores', $generadores)->with('table_gen_cm', $table_gen_cm)->with('carga_max_hoy', $carga_max_hoy)->with('carga_min_hoy', $carga_min_hoy);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {



        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data = $request->validate([

                'cg_valor' => 'required|numeric',
                'cg_generador' => 'required|numeric',
                'cg_hora' => 'required|string',
                'cg_fecha' => 'required|date',

            ], [
                'cg_valor.required' => 'qq',
                'cg_generador.required' => 'qqq',
                'cg_hora.required' => 'qq',
                'cg_fecha.required' => 'qqq',


                //'ciudad.required' => 'Seleccione una sede este campo es obligatorio.',
                //'fecha.required' => 'La fecha es obligatoria.',
                //'fecha.date' => 'La fecha debe ser un formato valido.',
                //'carga_max.required' => 'Los valores de carga maxima son obligatorias.',
                //'carga_max.numeric' => 'Los valores de carga maxima deben ser númerico.',

            ]);
            /*
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
            }*/
            $existingRecord = control_carga::where('gen_cg_fecha', $data['cg_fecha'])
                ->where('gen_cg_hora', $data['cg_hora'])
                ->where('gen_maquina_gen_ma_id', $data['cg_generador'])
                ->first();
            if ($existingRecord) {
                //Log::info(message: 'Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro para esta fecha, hora y maquina.'], 422);
            }

            $c_g_save = control_carga::create([
                'gen_cg_fecha' => $data['cg_fecha'],
                'gen_cg_hora' => $data['cg_hora'],
                'gen_cg_valor_kw' => $data['cg_valor'],
                'gen_maquina_gen_ma_id' => $data['cg_generador'],
            ]);



            Log::info('Record saved reporte diario : ', context: $c_g_save->toArray());
            // Log::info('Record saved carga maxima: ', context: $carga_max_save->toArray());
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



    public function buscar_control_carga_dia(Request $request)
    {

        if (!$request->isMethod('post')) {
            return redirect()->route('dash')->with('error', 'Método no permitido.');
        }

        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data = $request->validate([
                'fecha_buscar_c_c' => 'required|date', // Cambiar a string para pruebas
                // 'planta_id' => 'required|string',
            ], [
                'fecha_buscar_c_c.required' => 'La fecha es obligatoria.',
                'fecha_buscar_c_c.date' => 'La fecha debe ser un formato valido.',

                //'planta_id.required' => 'La planta es obligatoria.',
                //'planta_id.string' => 'La planta debe ser un texto valido.',

            ]);
            //$plantas = Planta::all();

            // Lógica para manejar la consulta
            $fecha_buscar_c_c = $request->input('fecha_buscar_c_c');
            //$plantaId = $request->input('planta_id');

            //list($year, $month) = explode('-', $fecha);
            //echo "Año: " . $year; // Salida: "Año: 2025"
            //echo "Mes: " . $month; // Salida: "Mes: 02"
            ////echo "planta: " . $planta; // Salida: "Mes: 02"

            // Aquí puedes agregar la lógica para procesar los datos y devolver una respuesta
            //return view('resultado', compact('fecha', 'plantaId'));


            // Realiza la consulta a la base de datos

            //$startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
            //$endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

            //$startOfLastMonth = Carbon::now()->startOfMonth();
            //$endOfLastMonth = Carbon::now()->endOfMonth();


            $ciudades = Ciudad::all();
            $generadores = Generador::all();

            $ciudades =  ciudad::all();
            //
            // $ciudades =  ciudad::all();

            // $fechaHoy = Carbon::now()->toDateString();
            $datos_hoy = DB::select("SELECT * FROM `gen_control_carga` WHERE gen_cg_fecha='$fecha_buscar_c_c' order by gen_cg_hora asc;");

            $table_gen_cm = DB::select("SELECT a.gen_cm_id, a.gen_cm_fecha,a.gen_cm_value, a.gen_ciudad_gen_ci_id, b.gen_ci_nombre FROM gen_carga_maxima as a, gen_ciudad as b where a.gen_ciudad_gen_ci_id = b.gen_ci_id;");

            $carga_max_hoy = DB::select("SELECT * FROM `gen_carga_maxima` WHERE gen_cm_fecha='$fecha_buscar_c_c';");
            $carga_min_hoy = DB::select("SELECT * FROM `gen_carga_minima` WHERE gen_cmi_fecha='$fecha_buscar_c_c';");


            //$datos_hoy = [
            // Tu arreglo de datos aquí
            //];

            // Organizar los datos por hora
            $tabla = [];
            foreach ($datos_hoy as $dato) {
                $hora = substr($dato->gen_cg_hora, 0, 2); // Obtener la hora en formato "HH"
                if (!isset($tabla[$hora])) {
                    $tabla[$hora] = [
                        'hora' => "~",
                        'MB 01' => "~",
                        'MB 02' => "~",
                        'MAN 01' => "~",
                        'MAN 02' => "~",
                        'EMD 09' => "~",
                        'EMD 10' => "~",
                        'EMD 11' => "~",
                        'EMD 12' => "~",
                        'EMD 13' => "~",
                        'EMD 14' => "~",

                        'TOTAL (KW)' => 0,
                    ];
                }


                if ($dato->gen_maquina_gen_ma_id == 1) {
                    $tabla[$hora]['ID MAN 01'] = $dato->gen_cg_id;
                    $tabla[$hora]['MAN 01'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 2) {
                    $tabla[$hora]['ID MAN 02'] = $dato->gen_cg_id;
                    $tabla[$hora]['MAN 02'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 3) {
                    $tabla[$hora]['ID MB 01'] = $dato->gen_cg_id;
                    $tabla[$hora]['MB 01'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 4) {
                    $tabla[$hora]['ID MB 02'] = $dato->gen_cg_id;
                    $tabla[$hora]['MB 02'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 5) {
                    $tabla[$hora]['ID EMD 09'] = $dato->gen_cg_id;
                    $tabla[$hora]['EMD 09'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 6) {
                    $tabla[$hora]['ID EMD 10'] = $dato->gen_cg_id;
                    $tabla[$hora]['EMD 10'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 7) {
                    $tabla[$hora]['ID EMD 11'] = $dato->gen_cg_id;
                    $tabla[$hora]['EMD 11'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 8) {
                    $tabla[$hora]['ID EMD 12'] = $dato->gen_cg_id;
                    $tabla[$hora]['EMD 12'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 9) {
                    $tabla[$hora]['ID EMD 13'] = $dato->gen_cg_id;
                    $tabla[$hora]['EMD 13'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 10) {
                    $tabla[$hora]['ID EMD 14'] = $dato->gen_cg_id;
                    $tabla[$hora]['EMD 14'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                };
                /* 
                if ($dato->gen_maquina_gen_ma_id == 1) {
                    $tabla[$hora]['MAN 01'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 2) {
                    $tabla[$hora]['MAN 02'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 3) {
                    $tabla[$hora]['MB 01'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 4) {
                    $tabla[$hora]['MB 02'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 5) {
                    $tabla[$hora]['EMD 09'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 6) {
                    $tabla[$hora]['EMD 10'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 7) {
                    $tabla[$hora]['EMD 11'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 8) {
                    $tabla[$hora]['EMD 12'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 9) {
                    $tabla[$hora]['EMD 13'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                } elseif ($dato->gen_maquina_gen_ma_id == 10) {
                    $tabla[$hora]['EMD 14'] = $dato->gen_cg_valor_kw;
                    $tabla[$hora]['hora'] = $dato->gen_cg_hora;
                }; */


                // Asignar el valor correspondiente a la columna adecuada
                $tabla[$hora]['TOTAL (KW)'] += $dato->gen_cg_valor_kw;
                // $tabla[$hora]['TOTAL (KW)'] += $dato->gen_maquina_gen_ma_id;
                // Aquí puedes agregar lógica para asignar valores a las columnas específicas


                //  return view('tu_vista', compact('tabla'));
            }



            //dd(compact('carga_max_hoy', 'datos_hoy'));



            DB::commit();


            //dd(compact('ciudades', 'datos_hoy', 'tabla', 'generadores', 'table_gen_cm', 'carga_max_hoy', 'carga_min_hoy'));
            return view('control_carga.crud')->with('ciudades', $ciudades)->with('datos_hoy', $datos_hoy)->with('tabla', $tabla)->with('generadores', $generadores)->with('table_gen_cm', $table_gen_cm)->with('carga_max_hoy', $carga_max_hoy)->with('carga_min_hoy', $carga_min_hoy);
            // return view('control_carga.crud')->with('datos_hoy', $datos_hoy)->with('tabla', $tabla)->with('generadores', $generadores)->with('table_gen_cm', $table_gen_cm)->with('carga_max_hoy', $carga_max_hoy)->with('carga_min_hoy', $carga_min_hoy);



            // dd(compact('fecha','year','month','labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2','hrs_acum1','hrs_acum1', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2','fecha_lts_1','comb_lts_1','comb_lts_2','aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2'));

            // return view('dash.graf_generales', compact('labels1', 'values1', 'labels2', 'values2', 'hrs_acum_1', 'hrs_acum_porce_1', 'hrs_acum_2', 'hrs_acum1', 'hrs_acum2', 'hrs_acum_porce_2', 'comb_acum1', 'comb_acum2', 'fech_comb1', 'fech_comb2', 'fecha_lts_1', 'comb_lts_1', 'comb_lts_2', 'aceite_acum1', 'fech_aceite1', 'aceite_acum2', 'fech_aceite2', 'plantas'));
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            /*  return view('dash.graf_generales_mes')->with($e->errors())->with($plantas); */
            // return view('dash.graf_error')->withErrors($e->errors())->with('plantas', $plantas = Planta::all());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            //  return view('dash.graf_error')->with('error', 'Ocurrió un error al procesar la solicitud.');
        }
    }


    public function actualizarMultiplesRegistros(Request $request)
    {
        $cm_id = $request->input('cm_id');
        $fecha = $request->input('fecha');
        $registros = $request->input('registros');


        if (!is_array($registros)) {
            return response()->json(['error' => 'Formato de datos inválido'], 422);
        }

        foreach ($registros as $registro) {
            $id = $registro['id'] ?? null;
            $campo = $registro['campo'] ?? null;
            $valor = $registro['valor'] ?? null;



            if ($id && $campo) {
                $modelo = Control_Carga::find($id); // Reemplaza TuModelo por el nombre real

                if ($modelo) {
                    $modelo->gen_cg_valor_kw  = $valor;

                    // Si quieres guardar también la fecha o cm_id en cada modelo:
                    // $modelo->cm_id = $cm_id;
                    // $modelo->fecha = $fecha;
                    Log::info("Campo : $campo para el registro ID: $id");
                    $modelo->save();
                } else {
                    // Opcional: registrar o devolver error si el campo no existe
                    Log::warning("Campo inválido: $campo para el registro ID: $id");
                }
            }
        }
        //dd(compact('cm_id', 'fecha', 'registros'));
        return response()->json(['success' => 'Registros actualizados correctamente']);
    }
}
