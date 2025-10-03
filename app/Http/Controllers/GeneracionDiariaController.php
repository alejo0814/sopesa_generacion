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
use App\Models\Registro_kw;
use App\Models\Reporte_Diario;
use App\Models\Registro_overhaul;
use Carbon\Carbon;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GeneracionDiariaController extends Controller
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
        $fechaHoy = Carbon::now()->toDateString();
        //datos que se colocan en la tabla para poder actualizar informacion
        /*  $as = DB::select("
            SELECT 
                b.gen_reh_fecha, 
                b.gen_reh_hora_trabajada, 
                b.gen_reh_hora_disponible, 
                e.gen_ma_nombre, 
                MAX(a.gen_maquina_gen_ma_id) AS gen_maquina_gen_ma_id, 
                MAX(a.gen_rek_lectura) AS gen_rek_lectura, 
                MAX(c.gen_rec_lectura) AS gen_rec_lectura, 
                MAX(d.gen_rea_lectura) AS gen_rea_lectura,
                MAX(a.gen_rek_gen_act) AS gen_rek_gen_act, 
                MAX(c.gen_rec_consumno) AS gen_rec_consumno, 
                MAX(d.gen_rea_consumo) AS gen_rea_consumo
            FROM 
                gen_registro_kw a
            JOIN 
                gen_registro_horas b ON a.gen_maquina_gen_ma_id = b.gen_maquina_gen_ma_id
            JOIN 
                gen_registro_combustible c ON a.gen_maquina_gen_ma_id = c.gen_maquina_gen_ma_id
            JOIN 
                gen_registro_aceite d ON a.gen_maquina_gen_ma_id = d.gen_maquina_gen_ma_id
            JOIN 
                gen_maquina e ON a.gen_maquina_gen_ma_id = e.gen_ma_id 
            WHERE 
                DATE(a.created_at) = '$fechaHoy' AND 
                DATE(b.created_at) = '$fechaHoy' AND 
                DATE(c.created_at) = '$fechaHoy' AND 
                DATE(d.created_at) = '$fechaHoy'
            GROUP BY 
                b.gen_reh_fecha, b.gen_reh_hora_trabajada, b.gen_reh_hora_disponible, e.gen_ma_nombre;
        "); */
        /* $as = DB::select("
        SELECT 
                b.gen_reh_fecha, 
                b.gen_reh_hora_trabajada, 
                b.gen_reh_hora_disponible,
                b.gen_reh_id,
                e.gen_ma_nombre, e.gen_ma_id,
                MAX(a.gen_maquina_gen_ma_id) AS gen_maquina_gen_ma_id,
                  a.gen_rek_id AS kw_id, 
                MAX(a.gen_rek_lectura) AS gen_rek_lectura, 
                MAX(c.gen_rec_lectura) AS gen_rec_lectura, 
                  c.gen_rec_id AS comb_id, 
                MAX(d.gen_rea_lectura) AS gen_rea_lectura,
                 d.gen_rea_id AS acei_id,
                MAX(a.gen_rek_gen_act) AS gen_rek_gen_act, 
                MAX(c.gen_rec_consumno) AS gen_rec_consumno, 
                MAX(d.gen_rea_consumo) AS gen_rea_consumo
            FROM 
                gen_registro_kw a
            JOIN 
                gen_registro_horas b ON a.gen_maquina_gen_ma_id = b.gen_maquina_gen_ma_id
            JOIN 
                gen_registro_combustible c ON a.gen_maquina_gen_ma_id = c.gen_maquina_gen_ma_id
            JOIN 
                gen_registro_aceite d ON a.gen_maquina_gen_ma_id = d.gen_maquina_gen_ma_id
            JOIN 
                gen_maquina e ON a.gen_maquina_gen_ma_id = e.gen_ma_id 
            WHERE 
                DATE(a.created_at) = '$fechaHoy' AND 
                DATE(b.created_at) = '$fechaHoy' AND 
                DATE(c.created_at) = '$fechaHoy' AND 
                DATE(d.created_at) = '$fechaHoy' AND
                a.gen_rek_fecha=b.gen_reh_fecha AND
                b.gen_reh_fecha=c.gen_rec_fecha AND
                b.gen_reh_fecha=d.gen_rea_fecha
            GROUP BY 
                b.gen_reh_fecha, b.gen_reh_hora_trabajada, b.gen_reh_hora_disponible, e.gen_ma_nombre, a.created_at, b.created_at, c.created_at, d.created_at
                ");
                
                SELECT 
    b.gen_reh_fecha, 
    b.gen_reh_hora_trabajada, 
    b.gen_reh_hora_disponible,
    b.gen_reh_id,
    e.gen_ma_nombre, 
    e.gen_ma_id,
    a.gen_maquina_gen_ma_id,
    a.gen_rek_id AS kw_id, 
    a.gen_rek_lectura, 
    c.gen_rec_lectura, 
    c.gen_rec_id AS comb_id, 
    d.gen_rea_lectura,
    d.gen_rea_id AS acei_id,
    a.gen_rek_gen_act, 
    c.gen_rec_consumno, 
    d.gen_rea_consumo
FROM 
    gen_registro_kw a
JOIN 
    gen_registro_horas b ON a.gen_maquina_gen_ma_id = b.gen_maquina_gen_ma_id
JOIN 
    gen_registro_combustible c ON a.gen_maquina_gen_ma_id = c.gen_maquina_gen_ma_id
JOIN 
    gen_registro_aceite d ON a.gen_maquina_gen_ma_id = d.gen_maquina_gen_ma_id
JOIN 
    gen_maquina e ON a.gen_maquina_gen_ma_id = e.gen_ma_id 
WHERE 
    DATE(a.created_at) = '$fechaHoy' AND 
    DATE(b.created_at) = '$fechaHoy' AND 
    DATE(c.created_at) = '$fechaHoy' AND 
    DATE(d.created_at) = '$fechaHoy' AND
    a.gen_rek_fecha = b.gen_reh_fecha AND
    b.gen_reh_fecha = c.gen_rec_fecha AND
    b.gen_reh_fecha = d.gen_rea_fecha
      AND 
    b.gen_reh_fecha = (SELECT MAX(gen_reh_fecha) FROM gen_registro_horas)
GROUP BY 
    b.gen_reh_fecha, 
    b.gen_reh_hora_trabajada, 
    b.gen_reh_hora_disponible, 
    b.gen_reh_id,
    e.gen_ma_nombre, 
    e.gen_ma_id,
    a.gen_maquina_gen_ma_id,
    a.gen_rek_id, 
    a.gen_rek_lectura, 
    c.gen_rec_lectura, 
    c.gen_rec_id, 
    d.gen_rea_lectura,
    d.gen_rea_id,
    a.gen_rek_gen_act, 
    c.gen_rec_consumno, 
    d.gen_rea_consumo




*/

        // Datos que se colocan en la tabla para poder actualizar información
        $as = DB::select("
            SELECT 
    b.gen_reh_fecha, 
    b.gen_reh_hora_trabajada, 
    b.gen_reh_hora_disponible,
    b.gen_reh_id,
    e.gen_ma_nombre, 
    e.gen_ma_id,
    a.gen_maquina_gen_ma_id,
    a.gen_rek_id AS kw_id, 
    a.gen_rek_lectura, 
    c.gen_rec_lectura, 
    c.gen_rec_id AS comb_id, 
    d.gen_rea_lectura,
    d.gen_rea_id AS acei_id,
    a.gen_rek_gen_act, 
    c.gen_rec_consumno, 
    d.gen_rea_consumo
FROM 
    gen_registro_kw a
JOIN 
    gen_registro_horas b ON a.gen_maquina_gen_ma_id = b.gen_maquina_gen_ma_id
JOIN 
    gen_registro_combustible c ON a.gen_maquina_gen_ma_id = c.gen_maquina_gen_ma_id
JOIN 
    gen_registro_aceite d ON a.gen_maquina_gen_ma_id = d.gen_maquina_gen_ma_id
JOIN 
    gen_maquina e ON a.gen_maquina_gen_ma_id = e.gen_ma_id 
JOIN 
    (SELECT gen_maquina_gen_ma_id, MAX(created_at) AS max_created_at
     FROM gen_registro_kw
     WHERE DATE(created_at) = '$fechaHoy'
     GROUP BY gen_maquina_gen_ma_id) sub
ON a.gen_maquina_gen_ma_id = sub.gen_maquina_gen_ma_id AND a.created_at = sub.max_created_at
WHERE 
    DATE(b.created_at) = '$fechaHoy' AND 
    DATE(c.created_at) = '$fechaHoy' AND 
    DATE(d.created_at) = '$fechaHoy' AND
    a.gen_rek_fecha = b.gen_reh_fecha AND
    b.gen_reh_fecha = c.gen_rec_fecha AND
    b.gen_reh_fecha = d.gen_rea_fecha
GROUP BY 
    b.gen_reh_fecha, 
    b.gen_reh_hora_trabajada, 
    b.gen_reh_hora_disponible, 
    b.gen_reh_id,
    e.gen_ma_nombre, 
    e.gen_ma_id,
    a.gen_maquina_gen_ma_id,
    a.gen_rek_id, 
    a.gen_rek_lectura, 
    c.gen_rec_lectura, 
    c.gen_rec_id, 
    d.gen_rea_lectura,
    d.gen_rea_id,
    a.gen_rek_gen_act, 
    c.gen_rec_consumno, 
    d.gen_rea_consumo;
");

        // Recuperar todas las plantas generadoras
        $plantas = Planta::all();
        // Recuperar todos los registros de la tabla generacion_diaria
        $generaciones_diarias = generacion_diaria::all();
        // Pasar los datos a la vista
        return view('gen_diaria.crud')->with('as', $as)->with('plantas', $plantas);
    }

    public function getGeneradores($planta_id)
    {
        $generadores = Generador::where('gen_planta_gen_pl_id', $planta_id)->get();
        return response()->json($generadores);
    }

    public function getPreviousDayData(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $fecha_ing = (new \DateTime($fecha))->format('Y-m-d');
            $fechaAnterior = (new \DateTime($fecha))->modify('-1 day')->format('Y-m-d');
            Log::info('Fecha anterior: ' . $fechaAnterior);

            // $data = Registro_hrs_trabajadas::where('gen_reh_fecha', $fechaAnterior)
            //   ->where('gen_maquina_gen_ma_id', $request->input('generador_id'))
            // ->first();


            /*  $resul_rep_fecha = DB::table('gen_registro_aceite')
                ->join('gen_registro_combustible as b', 'b.gen_maquina_gen_ma_id', '=', $request->input('generador_id'))
                ->join('gen_registro_kw as c', 'c.gen_maquina_gen_ma_id', '=', $request->input('generador_id'))
                ->join('gen_registro_horas as d', 'd.gen_maquina_gen_ma_id', '=', $request->input('generador_id'))
                ->select(' a.gen_rea_lectura', 'b.gen_rec_lectura', 'c.gen_rek_lectura', 'd.gen_reh_hora_trabajada')
                ->where('d.gen_reh_fecha',  $fecha)

                ->groupBy('d.gen_maquina_gen_ma_id')
                ->get(); */


            /*  $fecha = '2024-08-31'; */
            /*    $maquinaId = 2; */

            /* $resul_rep_fecha = DB::table('gen_registro_horas as d')
                ->join('gen_registro_aceite as a', 'd.gen_maquina_gen_ma_id', '=', 'a.gen_maquina_gen_ma_id')
                ->join('gen_registro_combustible as b', 'd.gen_maquina_gen_ma_id', '=', 'b.gen_maquina_gen_ma_id')
                ->join('gen_registro_kw as c', 'd.gen_maquina_gen_ma_id', '=', 'c.gen_maquina_gen_ma_id')
                ->select(
                    DB::raw('MAX(a.gen_rea_lectura) as gen_rea_lectura'),
                    DB::raw('MAX(b.gen_rec_lectura) as gen_rec_lectura'),
                    DB::raw('MAX(c.gen_rek_lectura) as gen_rek_lectura'),
                    DB::raw('MAX(d.gen_reh_hora_trabajada) as gen_reh_hora_trabajada'),
                    DB::raw('MAX(d.gen_reh_hora_disponible) as gen_reh_hora_disponible')
                )
                ->where('d.gen_reh_fecha', $fecha)
                ->where('d.gen_maquina_gen_ma_id', $request->input('generador_id'))
                ->groupBy('d.gen_maquina_gen_ma_id')
                ->first(); */


            $result = DB::table('gen_registro_horas as d')
                ->join('gen_registro_aceite as a', 'd.gen_maquina_gen_ma_id', '=', 'a.gen_maquina_gen_ma_id')
                ->join('gen_registro_combustible as b', 'd.gen_maquina_gen_ma_id', '=', 'b.gen_maquina_gen_ma_id')
                ->join('gen_registro_kw as c', 'd.gen_maquina_gen_ma_id', '=', 'c.gen_maquina_gen_ma_id')
                ->select(
                    DB::raw('MAX(a.gen_rea_lectura) as gen_rea_lectura'),
                    DB::raw('MAX(d.gen_reh_fecha) as gen_reh_fecha'),
                    DB::raw('MAX(b.gen_rec_lectura) as gen_rec_lectura'),
                    DB::raw('MAX(c.gen_rek_lectura) as gen_rek_lectura'),
                    DB::raw('MAX(d.gen_reh_hora_trabajada) as gen_reh_hora_trabajada'),
                    DB::raw('MAX(d.gen_reh_hora_disponible) as gen_reh_hora_disponible')
                )
                ->where('d.gen_reh_fecha', $fechaAnterior)
                ->where('a.gen_rea_fecha', $fechaAnterior)
                ->where('b.gen_rec_fecha', $fechaAnterior)
                ->where('c.gen_rek_fecha', $fechaAnterior)
                ->where('d.gen_maquina_gen_ma_id', $request->input('generador_id'))

                ->groupBy('d.gen_maquina_gen_ma_id')
                ->first();

            if ($result) {
                // Convertir el resultado a un array
                $data = json_decode(json_encode($result), true);
                // Agregar una variable adicional al array
                $data['fecha_ingresar'] = $fecha_ing;
                Log::info('Data encontrada: ', $data);
                return response()->json($data);
            } else {
                return response()->json(['error' => 'No se encuentran datos del día anterior.'], 404);
            }

            /*  if ($resul_rep_fecha) {
                return response()->json($resul_rep_fecha);
            } else {
                return response()->json(['error' => 'No se encuentran datos del día anterior.'], 404);
            } */

            /* 
                SELECT a.gen_rea_lectura, b.gen_rec_lectura, c.gen_rek_lectura, d.gen_reh_hora_trabajada, d.gen_reh_hora_disponible 
                
                
                FROM gen_registro_aceite a,gen_registro_combustible b, gen_registro_kw c, gen_registro_horas d 
                
                
                where d.gen_reh_fecha='2024-08-31' and d.gen_maquina_gen_ma_id=2 and a.gen_maquina_gen_ma_id=2 and b.gen_maquina_gen_ma_id=2 and c.gen_maquina_gen_ma_id=2 GROUP BY d.gen_maquina_gen_ma_id; */




            // Convierte el objeto a un array antes de pasarlo a Log::info()
            /*   Log::info('Data encontrada: ', $resul_rep_fecha ? $resul_rep_fecha->toArray() : []);

            if ($resul_rep_fecha) {
                return response()->json($resul_rep_fecha);
            } else {
                return response()->json(['error' => 'No se encuentran datos del día anterior.'], 404);
            } */
        } catch (\Exception $e) {
            Log::error('Error al obtener los datos del día anterior: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un problema al obtener los datos.'], 500);
        }
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
                'overh' => 'numeric',
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
            //$over= $data['overh'];
            $over = isset($data['overh']) ? $data['overh'] : 0;
            if ($over >= 1) {
                Log::info('se esta realizando un overhaul');
                $reg_over = Registro_overhaul::create([

                    'gen_over_fecha' => $data['fecha'],
                    'gen_maquina_gen_ma_id' => $data['generador_id']
                ]);
                Log::info('Overhaul saved: ', context: $reg_over->toArray());
            } else {
                Log::info('no se encuentran datos de overhaul');

                //return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            // Verificar si ya existe un registro para la fecha dada
            $existingRecord_hrs_trab = Registro_hrs_trabajadas::where('gen_reh_fecha', $data['fecha'])
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($existingRecord_hrs_trab) {
                Log::info(message: 'Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro para esta fecha y generador.'], 422);
            }
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
            if ($hrs_t_anterior) {
                Log::info('ok validacion dia anterior');
            } else {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anteriorr: ', context: $hrs_t_anterior->toArray());
            if ($over >= 1) {
                Log::info('se esta realizando un overhaul');
                $hrs_act = 0;
                $acum = 0;
                $hrs_ult_rep = 0;
            } else {
                Log::info('no se encuentran datos de overhaul');
                $hrs_act = $hrs_t_anterior['gen_reh_hora_actuales'] + $data['horas_trabajada'];
                $acum = $hrs_t_anterior['gen_reh_hora_acum'] + $data['horas_trabajada'];
                $hrs_ult_rep = $hrs_t_anterior['gen_registro_hora_ult_rep'] + $data['horas_trabajada'];

                //return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            // Guardar los datos en la tabla registro_horas
            $hrst = Registro_hrs_trabajadas::create([
                /*  'gen_reh_id', null o autoincrement*/
                /*  'gen_reh_id' => $data['generador_id'], */
                'gen_reh_fecha' => $data['fecha'],
                'gen_reh_hora_trabajada' => $data['horas_trabajada'],
                'gen_reh_hora_disponible' => $data['horas_disponible'],
                'gen_reh_hora_actuales' => $hrs_act,
                'gen_reh_hora_acum' => $acum,
                'gen_registro_hora_ult_rep' => $hrs_ult_rep,
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
            // Calcular la fecha anterior
            // Obtener las horas trabajadas del día anterior
            $lect_combu_anterior = Registro_combustible::where('gen_rec_fecha', $fechaAnterior)
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($lect_combu_anterior) {
                Log::info('ok validacion dia anterior');
            } else {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_combu_anterior->toArray());
            if ($over >= 1) {
                Log::info('se esta realizando un overhaul');
                $acum_comb = 0;
            } else {
                Log::info('no se encuentran datos de overhaul');
                $acum_comb = $data['lectura_combustible'] - $lect_combu_anterior['gen_rec_lectura'];
                //return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            //  Log::info('acumulado combustible: ', context: $acum_comb->toArray());
            Log::info('acumulado combustible: ' . $acum_comb);
            // Guardar los datos en la tabla registro_horas
            $lect_combustible = Registro_combustible::create([
                'gen_rec_fecha' => $data['fecha'],
                'gen_rec_lectura' => $data['lectura_combustible'],
                'gen_rec_consumno' => $acum_comb,
                'gen_maquina_gen_ma_id' => $data['generador_id']
            ]);
            Log::info('Record saved combustible: ', context: $lect_combustible->toArray());
            //guardar los datos del ACEITE-------------------------------------------------------------------
            $existingRecord_aceite_fecha = Registro_aceite::where('gen_rea_fecha', $data['fecha'])
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($existingRecord_aceite_fecha) {
                Log::info('Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro de combustible para esta fecha y generador.'], 422);
            }
            // Guardar los datos en la base de datos
            // Obtener las horas trabajadas del día anterior
            $lect_aceite_anterior = Registro_aceite::where('gen_rea_fecha', $fechaAnterior)
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($lect_aceite_anterior) {
                Log::info('ok validacion dia anterior');
            } else {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_aceite_anterior->toArray());
            Log::info('lectura aceite anterior: ' . $lect_aceite_anterior['gen_rea_consumno'] . 'lectura actual:' . $data['lectura_aceite']);
            if ($over >= 1) {
                Log::info('se esta realizando un overhaul');
                $acum_aceite = 0;
            } else {
                Log::info('no se encuentran datos de overhaul');
                $acum_aceite = ($data['lectura_aceite'] - $lect_aceite_anterior['gen_rea_lectura']) / 3.785;
                //return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            //  Log::info('acumulado combustible: ', context: $acum_aceite->toArray());
            Log::info('acumulado aceite: ' . $acum_aceite);
            // Guardar los datos en la tabla registro_horas

            $lect_aceite = Registro_aceite::create([

                'gen_rea_fecha' => $data['fecha'],
                'gen_rea_lectura' => $data['lectura_aceite'],
                'gen_rea_consumo' => $acum_aceite,
                'gen_maquina_gen_ma_id' => $data['generador_id']
            ]);
            Log::info('Record saved aceite: ', context: $lect_aceite->toArray());
            //guardar los datos del KW-------------------------------------------------------------------
            $existingRecord_kw_fecha = Registro_kw::where('gen_rek_fecha', $data['fecha'])
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($existingRecord_kw_fecha) {
                Log::info('Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro de kw para esta fecha y generador.'], 422);
            }

            $lect_kw_anterior = Registro_kw::where('gen_rek_fecha', $fechaAnterior)
                ->where('gen_maquina_gen_ma_id', operator: $data['generador_id'])
                ->first();

            if ($lect_kw_anterior) {
                Log::info('ok validacion dia anterior');
            } else {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de kw del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_kw_anterior->toArray());
            Log::info('lectura aceite anterior: ' . $lect_kw_anterior['gen_rea_consumno'] . 'lectura actual:' . $data['lectura_aceite']);
            if ($over >= 1) {
                Log::info('se esta realizando un overhaul');
                $kw_generados_actual = 0;
            } else {
                Log::info('no se encuentran datos de overhaul');



                // Si Lh < La, entonces el consumo (C) se calcula así:
                if ($data['lectura_kw'] < $lect_kw_anterior['gen_rek_lectura']) {
                    $vmm = 9999999;
                    $kw_generados_actual = ($vmm - $lect_kw_anterior['gen_rek_lectura']) + $data['lectura_kw'] + 1;
                } else {
                    $kw_generados_actual = $data['lectura_kw'] - $lect_kw_anterior['gen_rek_lectura'];
                }
                //$kw_generados_actual = $data['lectura_kw'] - $lect_kw_anterior['gen_rek_lectura'];
                //$kw_generados_actual = $data['lectura_kw'] - $lect_kw_anterior['gen_rek_lectura'];
                //return response()->json(['error' => 'No se encuentran datos del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            //  Log::info('acumulado combustible: ', context: $kw_generados_actual->toArray());
            Log::info('acumulado aceite: ' . $kw_generados_actual);
            // Guardar los datos en la tabla registro_horas
            $lect_kw = Registro_kw::create([

                'gen_rek_fecha' => $data['fecha'],
                'gen_rek_lectura' => $data['lectura_kw'],
                'gen_rek_gen_act' => $kw_generados_actual,
                'gen_maquina_gen_ma_id' => $data['generador_id'],
            ]);
            Log::info('Record saved kw: ', context: $lect_kw->toArray());

            //guardar reporte diario -------------------------------------------------------------------

            $existingRecord_rep_diario = Reporte_Diario::where('gen_repd_fecha', $data['fecha'])
                ->where('gen_maquina_gen_ma_id', $data['generador_id'])
                ->first();
            if ($existingRecord_rep_diario) {
                Log::info('Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro de kw para esta fecha y generador.'], 422);
            }


            $rep_diario_anterior = Reporte_Diario::where('gen_repd_fecha', $fechaAnterior)
                ->where('gen_maquina_gen_ma_id', operator: $data['generador_id'])
                ->first();
            if ($rep_diario_anterior) {
                Log::info('ok validacion dia anterior');
            } else {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de reporte del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            // Guardar los datos en la tabla registro_horas

            $rep_diario_save = Reporte_Diario::create([
                'gen_repd_fecha' => $data['fecha'],
                'gen_repd_gen_bruta' => $kw_generados_actual,
                'gen_repd_cons_propio' => 160 * $data['horas_trabajada'],
                'gen_repd_gen_neta' => $kw_generados_actual - (160 * $data['horas_trabajada']),
                'gen_repd_cap_nominal' => 14300,
                'gen_repd_cap_efectiva' => 14000,
                'gen_repd_carg_promedio' => $data['horas_trabajada'] != 0 ? $kw_generados_actual / $data['horas_trabajada'] : 0,
                'gen_repd_indice_carg_promed_nominal' => ($data['horas_trabajada'] != 0 && 14300 != 0) ? ($kw_generados_actual / (14300 * $data['horas_trabajada'])) * 100 : 0,
                'gen_repd_hrs_operacion' => $data['horas_trabajada'],
                'gen_repd_hrs_disponibilidad' => $data['horas_disponible'],
                'gen_repd_disp_generador' => 24 != 0 ? ($data['horas_disponible'] / 24) * 100 : 0,
                'gen_repd_cons_combustible_lts' => $acum_comb * 3.785,
                'gen_repd_cons_combustible_gal' => $acum_comb,
                'gen_repd_efi_comb_bruta' => $kw_generados_actual != 0 ? $acum_comb / $kw_generados_actual : 0,
                'gen_repd_efi_comb_neta' => ($kw_generados_actual - (160 * $data['horas_trabajada'])) != 0 ? $acum_comb / ($kw_generados_actual - (160 * $data['horas_trabajada'])) : 0,
                'gen_repd_con_comb_esp_bruto' => $kw_generados_actual != 0 ? (($acum_comb / $kw_generados_actual) * 3.7854 * 0.84 * 1000) : 0,
                'gen_repd_cons_comb_esp_neto' => ($kw_generados_actual - (160 * $data['horas_trabajada'])) != 0 ? (($acum_comb / ($kw_generados_actual - (160 * $data['horas_trabajada']))) * 3.7854 * 0.84 * 1000) : 0,
                'gen_repd_cons_aceite_gal' => $acum_aceite,
                'gen_repd_cons_aceite_lts' => $acum_aceite * 3.785,
                'gen_repd_hrs_trab_motor_tc' => $hrs_act,
                'gen_repd_hrs_last_overhaul' => 0,
                'gen_repd_hrs_last_mantenimiento' => 0,
                'gen_repd_hrs_trab_ace_lub_motor' => 0,
                'gen_maquina_gen_ma_id' => $data['generador_id']
            ]);
            Log::info('Record saved reporte diario : ', context: $rep_diario_save->toArray());


            // Confirmar la transacción
            DB::commit();

            Log::info('Datos de ingreso diario guardados correctamente en todas las tablas.');

            Log::info('Datos guardados correctamente los totales........... : ', context: $data);
            return response()->json(['success' => 'Datos guardados correctamente', 'data' => $data]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            return response()->json(['error' => 'Error de validación', 'type'=> 'dataForm', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar los datos', 'type'=> 'dataForm'], 500);
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






    public function actualizar(Request $request)
    {
        Log::info('Request method: ' . $request->method());
        Log::info('Request data: ', $request->all());


        try {
            // Iniciar una transacción
            DB::beginTransaction();
            // Validar los datos recibidos con mensajes personalizados
            $data_update = $request->validate([
                'gen_reh_fecha' => 'required|date',
                'num_maquina' => 'required|string',
                //'planta_id' => 'required|string',
                'gen_reh_hora_trabajada' => 'required|numeric',
                'gen_reh_hora_disponible' => 'required|numeric',
                'gen_rek_lectura' => 'required|numeric',
                'gen_rec_lectura' => 'required|numeric',
                'gen_rea_lectura' => 'required|numeric',
                'gen_rek_gen_act' => 'required|numeric',
                'gen_rec_consumno' => 'required|numeric',
                'gen_rea_consumo' => 'required|numeric',


            ], [
                'gen_reh_fecha.required' => 'La fecha es obligatoria.',
                'gen_reh_fecha.date' => 'La fecha debe ser un formato valido.',
                'num_maquina.required' => 'El generador es obligatorio.',
                'num_maquina.string' => 'El generador debe ser un texto valido.',
                //'planta_id.required' => 'La planta es obligatoria.',
                //'planta_id.string' => 'La planta debe ser un texto valido.',
                'gen_reh_hora_trabajada.required' => 'Las horas trabajadas son obligatorias.',
                'gen_reh_hora_trabajada.numeric' => 'Las horas trabajadas deben ser un número.',
                'gen_reh_hora_disponible.required' => 'Las horas disponibles son obligatorias.',
                'gen_reh_hora_disponible.numeric' => 'Las horas disponibles deben ser un número.',
                'gen_rek_lectura.required' => 'La lectura de kW es obligatoria.',
                'gen_rek_lectura.numeric' => 'La lectura de kW debe ser un número.',
                'gen_rec_lectura.required' => 'La lectura de combustible es obligatoria.',
                'gen_rec_lectura.numeric' => 'La lectura de combustible debe ser un número.',
                'gen_rea_lectura.required' => 'La lectura de aceite es obligatoria.',
                'gen_rea_lectura.numeric' => 'La lectura de aceite debe ser un número.',
                'gen_rek_gen_act.required' => 'La lectura de aceite es obligatoria.',
                'gen_rek_gen_act.numeric' => 'La lectura de aceite debe ser un número.',
                'gen_rec_consumno.required' => 'La lectura de aceite es obligatoria.',
                'gen_rec_consumno.numeric' => 'La lectura de aceite debe ser un número.',
                'gen_rea_consumo.required' => 'La lectura de aceite es obligatoria.',
                'gen_rea_consumo.numeric' => 'La lectura de aceite debe ser un número.',
            ]);
            return response()->json(['success' => 'Datos actualizados correctamente', 'data_update' => $data_update]);

            // Verificar si ya existe un registro para la fecha dada
            //$existingRecord_hrs_trab = Registro_hrs_trabajadas::where('gen_reh_fecha', $data['fecha'])
            //    ->where('gen_maquina_gen_ma_id', $data['generador_id'])
            //    ->first();
            //if ($existingRecord_hrs_trab) {
            //    // Actualizar los datos en la base de datos
            //    $existingRecord_hrs_trab->update($data);
            //    Log::info('Record updated: ', context: $existingRecord_hrs_trab->toArray());
            //} else {
            //    return response()->json(['error' => 'No se encuentra un registro para esta fecha y generador.'], 422);
            //}

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

    //public function update(Request $request, int $gen_maquina_gen_ma_id)
    //{

    //    //Log::info('dato que trae el id: ' . $id);
    //    try {
    //        // Iniciar una transacción
    //        DB::beginTransaction();
    //        // Validar los datos recibidos con mensajes personalizados
    //        $data_update = $request->validate([
    //            'gen_reh_fecha' => 'required|date',
    //            'num_maquina' => 'required|string',
    //            //'planta_id' => 'required|string',
    //            'gen_reh_hora_trabajada' => 'required|numeric',
    //            'gen_reh_hora_disponible' => 'required|numeric',
    //            'gen_rek_lectura' => 'required|numeric',
    //            'gen_rec_lectura' => 'required|numeric',
    //            'gen_rea_lectura' => 'required|numeric',
    //            'gen_rek_gen_act' => 'required|numeric',
    //            'gen_rec_consumno' => 'required|numeric',
    //            'gen_rea_consumo' => 'required|numeric',


    //        ], [
    //            'gen_reh_fecha.required' => 'La fecha es obligatoria.',
    //            'gen_reh_fecha.date' => 'La fecha debe ser un formato valido.',
    //            'num_maquina.required' => 'El generador es obligatorio.',
    //            'num_maquina.string' => 'El generador debe ser un texto valido.',
    //            //'planta_id.required' => 'La planta es obligatoria.',
    //            //'planta_id.string' => 'La planta debe ser un texto valido.',
    //            'gen_reh_hora_trabajada.required' => 'Las horas trabajadas son obligatorias.',
    //            'gen_reh_hora_trabajada.numeric' => 'Las horas trabajadas deben ser un número.',
    //            'gen_reh_hora_disponible.required' => 'Las horas disponibles son obligatorias.',
    //            'gen_reh_hora_disponible.numeric' => 'Las horas disponibles deben ser un número.',
    //            'gen_rek_lectura.required' => 'La lectura de kW es obligatoria.',
    //            'gen_rek_lectura.numeric' => 'La lectura de kW debe ser un número.',
    //            'gen_rec_lectura.required' => 'La lectura de combustible es obligatoria.',
    //            'gen_rec_lectura.numeric' => 'La lectura de combustible debe ser un número.',
    //            'gen_rea_lectura.required' => 'La lectura de aceite es obligatoria.',
    //            'gen_rea_lectura.numeric' => 'La lectura de aceite debe ser un número.',
    //            'gen_rek_gen_act.required' => 'La lectura de aceite es obligatoria.',
    //            'gen_rek_gen_act.numeric' => 'La lectura de aceite debe ser un número.',
    //            'gen_rec_consumno.required' => 'La lectura de aceite es obligatoria.',
    //            'gen_rec_consumno.numeric' => 'La lectura de aceite debe ser un número.',
    //            'gen_rea_consumo.required' => 'La lectura de aceite es obligatoria.',
    //            'gen_rea_consumo.numeric' => 'La lectura de aceite debe ser un número.',
    //        ]);

    //      


    //        // Buscar el registro por ID
    //        // Buscar el registro por dos condiciones
    //        $reg_update_aceite = Registro_aceite::where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
    //            ->where('gen_rea_fecha', $data_update['gen_reh_fecha'])
    //            ->firstOrFail();
    //           // Log::info('busqueda: ' . $reg_update_aceite);
    //        //actualizar los datos del ACEITE-------------------------------------------------------------------
    //        // Calcular la fecha anterior a la modificar 
    //        $fechaAnteriorUpdate = (new \DateTime($data_update['gen_reh_fecha']))->modify('-1 day')->format('Y-m-d');
    //        // Guardar los datos en la base de datos

    //        // Obtener las horas trabajadas del día anterior
    //        $lect_aceite_anteriorU = Registro_aceite::where('gen_rea_fecha', $fechaAnteriorUpdate)
    //            ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
    //            ->first();
    //        if ($lect_aceite_anteriorU) {
    //            //Log::info('ok validacion dia anterior');
    //        } else {
    //           // Log::info('no se encuentran datos del dia anterior');
    //            return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
    //        }
    //       // Log::info('fecha anterior: ', context: $lect_aceite_anteriorU->toArray());
    //      //  Log::info('lectura aceite anterior: ' . $lect_aceite_anteriorU['gen_rea_consumno']);


    //        $acum_aceite_update = ($data_update['gen_rea_lectura'] - $lect_aceite_anteriorU['gen_rea_lectura']) / 3.785;
    //        //  Log::info('acumulado combustible: ', context: $acum_aceite_update->toArray());
    //        //Log::info('acumulado aceite: ' . $acum_aceite_update);
    //        // Guardar los datos en la tabla registro_horas

    //      // $lect_aceite = Registro_aceite::create([

    //      //     'gen_rea_fecha' => $data['fecha'],
    //      //     'gen_rea_lectura' => $data['lectura_aceite'],
    //      //     'gen_rea_consumo' => $acum_aceite_update,
    //      //     'gen_maquina_gen_ma_id' => $data['generador_id']
    //      // ]);
    //      // Log::info('Record saved aceite: ', context: $lect_aceite->toArray());
    //      //Log::info('Valor del ID recibido: ' . $id);
    //      if (is_null($gen_maquina_gen_ma_id)) {
    //        return response()->json(['error' => 'El ID no puede ser null'], 422);
    //    }
    //        // Actualizar los campos del registro
    //        $reg_update_aceite->gen_rea_lectura = $data_update['gen_rea_lectura'];
    //        $reg_update_aceite->gen_rea_consumo = $acum_aceite_update;
    //        // Actualiza más campos según sea necesario
    //og::info('dato a enviar: ' . $reg_update_aceite->gen_rea_lectura = $data_update['gen_rea_lectura']);
    //og::info('dato id: ' . $gen_maquina_gen_ma_id);
    //        // Guardar los cambios en la base de datos
    //        $reg_update_aceite->save();
    //     
    //       // DB::commit();
    //        // Redirigir o devolver una respuesta
    //        // return redirect()->route('ruta.deseada')->with('success', 'Registro actualizado correctamente');

    ////d($reg_update_aceite,$acum_aceite_update); // Esto detendrá la ejecución y mostrará los datos recuperados



    //        return response()->json(['success' => 'Datos actualizados correctamente', 'data_update' => $data_update]);






    //        // return response()->json(['success' => 'Datos actualizados correctamente', 'data_update' => $data_update]);

    //        // Verificar si ya existe un registro para la fecha dada
    //        //$existingRecord_hrs_trab = Registro_hrs_trabajadas::where('gen_reh_fecha', $data['fecha'])
    //        //    ->where('gen_maquina_gen_ma_id', $data['generador_id'])
    //        //    ->first();
    //        //if ($existingRecord_hrs_trab) {
    //        //    // Actualizar los datos en la base de datos
    //        //    $existingRecord_hrs_trab->update($data);
    //        //    Log::info('Record updated: ', context: $existingRecord_hrs_trab->toArray());
    //        //} else {
    //        //    return response()->json(['error' => 'No se encuentra un registro para esta fecha y generador.'], 422);
    //        //}

    //    } catch (ValidationException $e) {
    //        DB::rollBack();
    //        Log::error('Validation error: ', $e->errors());
    //        return response()->json(['error' => 'Error de validación', 'messages' => $e->errors()], 422);
    //    } catch (\Exception $e) {
    //        DB::rollBack();
    //        Log::error('General error: ' . $e->getMessage());
    //        return response()->json(['error' => 'Error al guardar los datos'], 500);
    //    }
    //}




    /*  public function update(Request $request, int $gen_maquina_gen_ma_id)
   {
       Log::info('dato que trae el id: ' . $gen_maquina_gen_ma_id);
   
       if (is_null($gen_maquina_gen_ma_id)) {
           return response()->json(['error' => 'El ID no puede ser null'], 422);
       }
   
       try {
           DB::beginTransaction();
   
           $data_update = $request->validate([
               'gen_reh_fecha' => 'required|date',
               'num_maquina' => 'required|string',
               'gen_reh_hora_trabajada' => 'required|numeric',
               'gen_reh_hora_disponible' => 'required|numeric',
               'gen_rek_lectura' => 'required|numeric',
               'gen_rec_lectura' => 'required|numeric',
               'gen_rea_lectura' => 'required|numeric',
               'gen_rek_gen_act' => 'required|numeric',
               'gen_rec_consumno' => 'required|numeric',
               'gen_rea_consumo' => 'required|numeric',
           ], [
               'gen_reh_fecha.required' => 'La fecha es obligatoria.',
               'gen_reh_fecha.date' => 'La fecha debe ser un formato valido.',
               'num_maquina.required' => 'El generador es obligatorio.',
               'num_maquina.string' => 'El generador debe ser un texto valido.',
               'gen_reh_hora_trabajada.required' => 'Las horas trabajadas son obligatorias.',
               'gen_reh_hora_trabajada.numeric' => 'Las horas trabajadas deben ser un número.',
               'gen_reh_hora_disponible.required' => 'Las horas disponibles son obligatorias.',
               'gen_reh_hora_disponible.numeric' => 'Las horas disponibles deben ser un número.',
               'gen_rek_lectura.required' => 'La lectura de kW es obligatoria.',
               'gen_rek_lectura.numeric' => 'La lectura de kW debe ser un número.',
               'gen_rec_lectura.required' => 'La lectura de combustible es obligatoria.',
               'gen_rec_lectura.numeric' => 'La lectura de combustible debe ser un número.',
               'gen_rea_lectura.required' => 'La lectura de aceite es obligatoria.',
               'gen_rea_lectura.numeric' => 'La lectura de aceite debe ser un número.',
               'gen_rek_gen_act.required' => 'La lectura de aceite es obligatoria.',
               'gen_rek_gen_act.numeric' => 'La lectura de aceite debe ser un número.',
               'gen_rec_consumno.required' => 'La lectura de aceite es obligatoria.',
               'gen_rec_consumno.numeric' => 'La lectura de aceite debe ser un número.',
               'gen_rea_consumo.required' => 'La lectura de aceite es obligatoria.',
               'gen_rea_consumo.numeric' => 'La lectura de aceite debe ser un número.',
           ]);
   
           // Buscar el registro por las condiciones especificadas
           $reg_update_aceite = Registro_aceite::where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
               ->where('gen_rea_fecha', $data_update['gen_reh_fecha'])
               ->firstOrFail();
   
           Log::info('busqueda: ' . $reg_update_aceite);
   
           $fechaAnteriorUpdate = (new \DateTime($data_update['gen_reh_fecha']))->modify('-1 day')->format('Y-m-d');
   
           $lect_aceite_anteriorU = Registro_aceite::where('gen_rea_fecha', $fechaAnteriorUpdate)
               ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
               ->first();
   
           if (!$lect_aceite_anteriorU) {
               Log::info('no se encuentran datos del dia anterior');
               return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
           }
   
           Log::info('fecha anterior: ', context: $lect_aceite_anteriorU->toArray());
           Log::info('lectura aceite anterior: ' . $lect_aceite_anteriorU['gen_rea_consumno']);
   
           $acum_aceite_update = ($data_update['gen_rea_lectura'] - $lect_aceite_anteriorU['gen_rea_lectura']) / 3.785;
           Log::info('acumulado aceite: ' . $acum_aceite_update);
   
           // Actualizar los campos del registro
           $reg_update_aceite->gen_rea_lectura = $data_update['gen_rea_lectura'];
           $reg_update_aceite->gen_rea_consumo = $acum_aceite_update;
   
           Log::info('dato a enviar: ' . $reg_update_aceite->gen_rea_lectura);
           Log::info('dato id: ' . $gen_maquina_gen_ma_id);
   

           $reg_update_aceite = DB::table('gen_registro_aceite')
           ->where('gen_rea_fecha', $data_update['gen_reh_fecha'])
           ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
           ->update(['gen_rea_lectura' =>$data_update['gen_rea_lectura'],'gen_rea_consumo' =>$acum_aceite_update]);

            //Log::info('nnnnnnnnnnnnnnnnnnnnnnn: ' . context: $reg_update_aceite->toArray());
           
           //$reg_update_aceite->save();
   
           DB::commit();
   
           return response()->json(['success' => 'Datos actualizados correctamente', 'data_update' => $reg_update_aceite]);
   
       } catch (ValidationException $e) {
           DB::rollBack();
           Log::error('Validation error: ', $e->errors());
           return response()->json(['error' => 'Error de validación', 'messages' => $e->errors()], 422);
       } catch (\Exception $e) {
           DB::rollBack();
           Log::error('General error: ' . $e->getMessage());
           return response()->json(['error' => 'Error al guardar los datos'], 500);
       }
   } */

    public function update(Request $request, int $gen_maquina_gen_ma_id)
    {
        Log::info('dato que trae el id: ' . $gen_maquina_gen_ma_id);

        if (is_null($gen_maquina_gen_ma_id)) {
            return response()->json(['error' => 'El ID no puede ser null'], 422);
        }

        try {
            DB::beginTransaction();

            $data_update = $request->validate([
                'gen_reh_fecha' => 'required|date',
                'num_maquina' => 'required|string',
                'gen_reh_hora_trabajada' => 'required|numeric',
                'gen_reh_hora_disponible' => 'required|numeric',
                'gen_rek_lectura' => 'required|numeric',
                'gen_rec_lectura' => 'required|numeric',
                'gen_rea_lectura' => 'required|numeric',
                'gen_rek_gen_act' => 'required|numeric',
                'gen_rec_consumno' => 'required|numeric',
                'gen_rea_consumo' => 'required|numeric',
            ], [
                'gen_reh_fecha.required' => 'La fecha es obligatoria.',
                'gen_reh_fecha.date' => 'La fecha debe ser un formato valido.',
                'num_maquina.required' => 'El generador es obligatorio.',
                'num_maquina.string' => 'El generador debe ser un texto valido.',
                'gen_reh_hora_trabajada.required' => 'Las horas trabajadas son obligatorias.',
                'gen_reh_hora_trabajada.numeric' => 'Las horas trabajadas deben ser un número.',
                'gen_reh_hora_disponible.required' => 'Las horas disponibles son obligatorias.',
                'gen_reh_hora_disponible.numeric' => 'Las horas disponibles deben ser un número.',
                'gen_rek_lectura.required' => 'La lectura de kW es obligatoria.',
                'gen_rek_lectura.numeric' => 'La lectura de kW debe ser un número.',
                'gen_rec_lectura.required' => 'La lectura de combustible es obligatoria.',
                'gen_rec_lectura.numeric' => 'La lectura de combustible debe ser un número.',
                'gen_rea_lectura.required' => 'La lectura de aceite es obligatoria.',
                'gen_rea_lectura.numeric' => 'La lectura de aceite debe ser un número.',
                'gen_rek_gen_act.required' => 'La lectura de aceite es obligatoria.',
                'gen_rek_gen_act.numeric' => 'La lectura de aceite debe ser un número.',
                'gen_rec_consumno.required' => 'La lectura de aceite es obligatoria.',
                'gen_rec_consumno.numeric' => 'La lectura de aceite debe ser un número.',
                'gen_rea_consumo.required' => 'La lectura de aceite es obligatoria.',
                'gen_rea_consumo.numeric' => 'La lectura de aceite debe ser un número.',
            ]);

            // Registrar los valores antes de la consulta
            Log::info('gen_maquina_gen_ma_id: ' . $gen_maquina_gen_ma_id);
            Log::info('maquina ' . $data_update['num_maquina']);
            Log::info('datos recibidos: ' . $data_update['gen_reh_fecha']);



            // tabla aceite -- Buscar el registro por las condiciones especificadas 
            $reg_update_aceite = Registro_aceite::where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->where('gen_rea_fecha', $data_update['gen_reh_fecha'])
                ->firstOrFail();

            Log::info('busqueda: ' . $reg_update_aceite);

            $fechaAnteriorUpdate = (new \DateTime($data_update['gen_reh_fecha']))->modify('-1 day')->format('Y-m-d');

            $lect_aceite_anteriorU = Registro_aceite::where('gen_rea_fecha', $fechaAnteriorUpdate)
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->first();
            if (!$lect_aceite_anteriorU) {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_aceite_anteriorU->toArray());
            Log::info('lectura aceite anterior: ' . $lect_aceite_anteriorU['gen_rea_consumno']);
            $acum_aceite_update = ($data_update['gen_rea_lectura'] - $lect_aceite_anteriorU['gen_rea_lectura']) / 3.785;
            Log::info('acumulado aceite: ' . $acum_aceite_update);
            // Actualizar los campos del registro utilizando el constructor de consultas
            DB::table('gen_registro_aceite')
                ->where('gen_rea_fecha', $data_update['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->update([
                    'gen_rea_lectura' => $data_update['gen_rea_lectura'],
                    'gen_rea_consumo' => $acum_aceite_update,
                    'updated_at' => now() // Asegúrate de actualizar la columna de timestamp si existe
                ]);
            DB::commit();
            Log::info('Record saved reporte diario : ', context: $data_update);






            // ---------------------tabla combustible -- Buscar el registro por las condiciones especificadas --------------------------
            $reg_update_comb = Registro_combustible::where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->where('gen_rec_fecha', $data_update['gen_reh_fecha'])
                ->firstOrFail();

            Log::info('busqueda: ' . $reg_update_comb);

            $fechaAnteriorUpdate = (new \DateTime($data_update['gen_reh_fecha']))->modify('-1 day')->format('Y-m-d');

            $lect_comb_anteriorU = Registro_combustible::where('gen_rec_fecha', $fechaAnteriorUpdate)
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->first();
            if (!$lect_comb_anteriorU) {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_comb_anteriorU->toArray());
            Log::info('lectura combustible anterior: ' . $lect_comb_anteriorU['gen_rea_consumno']);
            //$acum_comb_update = ($data_update['gen_rec_lectura'] - $lect_comb_anteriorU['gen_rec_lectura']) / 3.785;
            $acum_comb_update = ($data_update['gen_rec_lectura'] - $lect_comb_anteriorU['gen_rec_lectura']);
            Log::info('acumulado combustible: ' . $acum_comb_update);
            // Actualizar los campos del registro utilizando el constructor de consultas
            DB::table('gen_registro_combustible')
                ->where('gen_rec_fecha', $data_update['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->update([
                    'gen_rec_lectura' => $data_update['gen_rec_lectura'],
                    'gen_rec_consumno' => $acum_comb_update,
                    'updated_at' => now() // Asegúrate de actualizar la columna de timestamp si existe
                ]);
            DB::commit();
            Log::info('Record updated combustible : ', context: $data_update);



            // ---------------------tabla KW -- Buscar el registro por las condiciones especificadas --------------------------
            $reg_update_kw = Registro_kw::where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->where('gen_rek_fecha', $data_update['gen_reh_fecha'])
                ->firstOrFail();

            Log::info('busqueda: ' . $reg_update_kw);

            $fechaAnteriorUpdate = (new \DateTime($data_update['gen_reh_fecha']))->modify('-1 day')->format('Y-m-d');

            $lect_kw_anteriorU = Registro_kw::where('gen_rek_fecha', $fechaAnteriorUpdate)
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->first();
            if (!$lect_kw_anteriorU) {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_kw_anteriorU->toArray());
            Log::info('lectura combustible anterior: ' . $lect_kw_anteriorU['gen_rea_consumno']);



            $gen_act_kw_update = ($data_update['gen_rek_lectura'] - $lect_kw_anteriorU['gen_rek_lectura']);
            Log::info('acumulado combustible: ' . $gen_act_kw_update);
            // Actualizar los campos del registro utilizando el constructor de consultas
            DB::table('gen_registro_kw')
                ->where('gen_rek_fecha', $data_update['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->update([
                    'gen_rek_lectura' => $data_update['gen_rek_lectura'],
                    'gen_rek_gen_act' => $gen_act_kw_update,
                    'updated_at' => now() // Asegúrate de actualizar la columna de timestamp si existe
                ]);
            DB::commit();
            Log::info('Record updated kw : ', context: $data_update);






            // ---------------------tabla horas trabajadas -- Buscar el registro por las condiciones especificadas --------------------------


            $reg_update_hrs = Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->where('gen_reh_fecha', $data_update['gen_reh_fecha'])
                ->firstOrFail();

            Log::info('busqueda: ' . $reg_update_hrs);

            $fechaAnteriorUpdate = (new \DateTime($data_update['gen_reh_fecha']))->modify('-1 day')->format('Y-m-d');

            $lect_hrs_anteriorU = Registro_hrs_trabajadas::where('gen_reh_fecha', $fechaAnteriorUpdate)
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->first();
            if (!$lect_hrs_anteriorU) {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de combustible del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }
            Log::info('fecha anterior: ', context: $lect_hrs_anteriorU->toArray());
            Log::info('lectura combustible anterior: ' . $lect_hrs_anteriorU['gen_rea_consumno']);
            $gen_act_hrs_update = ($data_update['gen_rek_lectura'] - $lect_hrs_anteriorU['gen_rek_lectura']) / 3.785;
            Log::info('acumulado combustible: ' . $gen_act_hrs_update);
            // Actualizar los campos del registro utilizando el constructor de consultas


            $hrs_act_u = $lect_hrs_anteriorU['gen_reh_hora_actuales'] + $data_update['gen_reh_hora_trabajada'];
            $acum_u = $lect_hrs_anteriorU['gen_reh_hora_acum'] + $data_update['gen_reh_hora_trabajada'];
            $hrs_ult_rep_u = $lect_hrs_anteriorU['gen_registro_hora_ult_rep'] + $data_update['gen_reh_hora_trabajada'];



            DB::table('gen_registro_horas')
                ->where('gen_reh_fecha', $data_update['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->update([
                    'gen_reh_hora_trabajada' => $data_update['gen_reh_hora_trabajada'],
                    'gen_reh_hora_disponible' => $data_update['gen_reh_hora_disponible'],
                    'gen_reh_hora_actuales' => $hrs_act_u,
                    'gen_reh_hora_acum' => $acum_u,
                    'gen_registro_hora_ult_rep' => $hrs_ult_rep_u,

                    'updated_at' => now() // Asegúrate de actualizar la columna de timestamp si existe
                ]);
            DB::commit();
            Log::info('Record updated hrs : ', context: $data_update);







            //guardar reporte diario -------------------------------------------------------------------

            $existingRecord_rep_diario_u = Reporte_Diario::where('gen_repd_fecha', $data_update['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->first();
            /*     if ($existingRecord_rep_diario_u) {
                Log::info('Ya existe un registro');
                return response()->json(['error' => 'Ya existe un registro de kw para esta fecha y generador.'], 422);
            }
 */

            $rep_diario_anterior_u = Reporte_Diario::where('gen_repd_fecha', $fechaAnteriorUpdate)
                ->where('gen_maquina_gen_ma_id', operator: $gen_maquina_gen_ma_id)
                ->first();
            if ($rep_diario_anterior_u) {
                Log::info('ok validacion dia anterior');
            } else {
                Log::info('no se encuentran datos del dia anterior');
                return response()->json(['error' => 'No se encuentran datos de reporte del dia anterior, por favor verifique la fecha ingresada.'], 422);
            }

            // Guardar los datos en la tabla registro_horas

            DB::table('gen_rep_diario')
                ->where('gen_repd_fecha', $data_update['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $gen_maquina_gen_ma_id)
                ->update([
                    'gen_repd_fecha' => $data_update['gen_reh_fecha'],
                    'gen_repd_gen_bruta' => $gen_act_kw_update,
                    'gen_repd_cons_propio' => 160 * $data_update['gen_reh_hora_trabajada'],
                    'gen_repd_gen_neta' => $gen_act_kw_update - (160 * $data_update['gen_reh_hora_trabajada']),
                    'gen_repd_cap_nominal' => 14300,
                    'gen_repd_cap_efectiva' => 14000,
                    'gen_repd_carg_promedio' => $data_update['gen_reh_hora_trabajada'] != 0 ? $gen_act_kw_update / $data_update['gen_reh_hora_trabajada'] : 0,
                    'gen_repd_indice_carg_promed_nominal' => ($data_update['gen_reh_hora_trabajada'] != 0 && 14300 != 0) ? ($gen_act_kw_update / (14300 * $data_update['gen_reh_hora_trabajada'])) * 100 : 0,
                    'gen_repd_hrs_operacion' => $data_update['gen_reh_hora_trabajada'],
                    'gen_repd_hrs_disponibilidad' => $data_update['gen_reh_hora_disponible'],
                    'gen_repd_disp_generador' => 24 != 0 ? ($data_update['gen_reh_hora_disponible'] / 24) * 100 : 0,
                    'gen_repd_cons_combustible_lts' => $acum_comb_update * 3.785,
                    'gen_repd_cons_combustible_gal' => $acum_comb_update,
                    'gen_repd_efi_comb_bruta' => $gen_act_kw_update != 0 ? $acum_comb_update / $gen_act_kw_update : 0,
                    'gen_repd_efi_comb_neta' => ($gen_act_kw_update - (160 * $data_update['gen_reh_hora_trabajada'])) != 0 ? $acum_comb_update / ($gen_act_kw_update - (160 * $data_update['gen_reh_hora_trabajada'])) : 0,
                    'gen_repd_con_comb_esp_bruto' => $gen_act_kw_update != 0 ? (($acum_comb_update / $gen_act_kw_update) * 3.7854 * 0.84 * 1000) : 0,
                    'gen_repd_cons_comb_esp_neto' => ($gen_act_kw_update - (160 * $data_update['gen_reh_hora_trabajada'])) != 0 ? (($acum_comb_update / ($gen_act_kw_update - (160 * $data_update['gen_reh_hora_trabajada']))) * 3.7854 * 0.84 * 1000) : 0,
                    'gen_repd_cons_aceite_gal' => $acum_aceite_update,
                    'gen_repd_cons_aceite_lts' => $acum_aceite_update * 3.785,
                    'gen_repd_hrs_trab_motor_tc' => $hrs_act_u,
                    'gen_repd_hrs_last_overhaul' => 0,
                    'gen_repd_hrs_last_mantenimiento' => 0,
                    'gen_repd_hrs_trab_ace_lub_motor' => 0,

                    'updated_at' => now() // Asegúrate de actualizar la columna de timestamp si existe
                ]);


            DB::commit();
            Log::info('Record updated hrs : ', context: $data_update);








            return response()->json(['success' => 'Datos actualizados correctamente', 'data_update' => $data_update]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Model not found: ' . $e->getMessage());
            return response()->json(['error' => 'No se encontraron resultados para el modelo especificado'], 404);
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



    public function destroy(string $id)
    {
        //
    }


    public function delete(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $data_delete = $request->validate([
                'gen_reh_fecha' => 'required|date',
                'num_maquina' => 'required|string',
            ], [
                'gen_reh_fecha.required' => 'La fecha es obligatoria.',
                'gen_reh_fecha.date' => 'La fecha debe ser un formato valido.',
                'num_maquina.required' => 'El generador es obligatorio.',
                'num_maquina.string' => 'El generador debe ser un texto valido.',
            ]);

            $gen_maquina_gen_ma_id = $request->input('gen_maquina_gen_ma_id');


            //Log::info('busqueda: ' . $data_delete);
            // Eliminar registros de la tabla aceite
            Registro_aceite::where('gen_maquina_gen_ma_id', $id)
                ->where('gen_rea_fecha', $data_delete['gen_reh_fecha'])
                ->delete();
            // Log::info('busqueda: ' . $delete1);

            // Eliminar registros de la tabla combustible
            Registro_combustible::where('gen_maquina_gen_ma_id', $id)
                ->where('gen_rec_fecha', $data_delete['gen_reh_fecha'])
                ->delete();
            //
            //// Eliminar registros de la tabla KW
            Registro_kw::where('gen_maquina_gen_ma_id', $id)
                ->where('gen_rek_fecha', $data_delete['gen_reh_fecha'])
                ->delete();
            //
            //// Eliminar registros de la tabla horas trabajadas
            Registro_hrs_trabajadas::where('gen_maquina_gen_ma_id', $id)
                ->where('gen_reh_fecha', $data_delete['gen_reh_fecha'])
                ->delete();
            //
            //// Eliminar registros de la tabla reporte diario
            Reporte_Diario::where('gen_repd_fecha', $data_delete['gen_reh_fecha'])
                ->where('gen_maquina_gen_ma_id', $id)
                ->delete();

            DB::commit();
            Log::info('All records deleted successfully : ', context: $data_delete);

            return response()->json(['success' => 'Registros eliminados correctamente.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting records: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar los registros.'], 500);
        }
    }
}
