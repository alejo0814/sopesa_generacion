<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteMesController extends Controller
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

        return view('reportes.crud', compact('data1', 'plantas', 'rep', 'last_rep','cmm'));
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


            return view('reportes.rep_diario')->with('resul_rep_fecha', $resul_rep_fecha)->with('cm', $cm);
        //return view('publicaciones.pub_busqueda', ['valorSeleccionado' => $valorSeleccionado]);
       // return view('reportes.rep_diario', ['resul_rep_fecha' => $resul_rep_fecha]);
        //return response()->json(['est_123'=> $est_123], $publi_año);

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
