<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte_Diario;
use App\Models\Planta;
use App\Models\Generador;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\App;
use PDF;


class ReportePdfController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function generarPDF()
    {
      //  $last_rep = // Obtén los datos necesarios para la vista

            //$pdf = PDF::loadView('crud', compact('last_rep'));




        $rep = Reporte_Diario::all();
        $data1 = Reporte_Diario::all();
        $plantas = Planta::all();
        $last_rep = DB::table('gen_rep_diario')
            ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
            ->select('*')
            ->where('gen_repd_fecha', DB::raw('(select MAX(gen_repd_fecha) from gen_rep_diario)'))
            ->orderBy('gen_maquina_gen_ma_id', 'ASC')
            ->get();


        $dompdf = App::make("dompdf.wrapper");
        $dompdf->loadView("reportes_pdf.reporte_diario_pdf", [
            "last_rep" => $last_rep,
            "plantas" => $plantas,
            //"est_456" => $est_456,
           // "no_res" => $no_res,
           // "datos_cu" => $datos_cu,
           // "año" => $año,
            //"mes" => $mm,
            //'image' => $this->convertImageToBase64(public_path('\img\SOPESA_LOGO.jpg'))
            /*   "est_123" => $est_123,
                 "est_456" => $est_456,
                 "no_res" => $no_res,
                 "datos_cu" => $datos_cu, */
        ])->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape')->set_option('dpi', 9000);
        return $dompdf->stream();

        //return $pdf->download('reporte.pdf');
        //return view('reportes.crud', compact('data1', 'plantas', 'rep', 'last_rep'));
    }
    public function pdf_x_fecha($fecha)
    {
      //  $last_rep = // Obtén los datos necesarios para la vista

            //$pdf = PDF::loadView('crud', compact('last_rep'));




        $rep = Reporte_Diario::all();
        $data1 = Reporte_Diario::all();
        $plantas = Planta::all();
        $last_rep = DB::table('gen_rep_diario')
            ->join('gen_maquina', 'gen_rep_diario.gen_maquina_gen_ma_id', '=', 'gen_maquina.gen_ma_id')
            ->select('*')
            ->where('gen_repd_fecha', $fecha)
            ->orderBy('gen_maquina_gen_ma_id', 'ASC')
            ->get();


        $dompdf = App::make("dompdf.wrapper");
        $dompdf->loadView("reportes_pdf.reporte_diario_pdf", [
            "last_rep" => $last_rep,
            "plantas" => $plantas,
            //"est_456" => $est_456,
           // "no_res" => $no_res,
           // "datos_cu" => $datos_cu,
           // "año" => $año,
            //"mes" => $mm,
            //'image' => $this->convertImageToBase64(public_path('\img\SOPESA_LOGO.jpg'))
            /*   "est_123" => $est_123,
                 "est_456" => $est_456,
                 "no_res" => $no_res,
                 "datos_cu" => $datos_cu, */
        ])->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape')->set_option('dpi', 9000);
        return $dompdf->stream();

        //return $pdf->download('reporte.pdf');
        //return view('reportes.crud', compact('data1', 'plantas', 'rep', 'last_rep'));
    }
}
