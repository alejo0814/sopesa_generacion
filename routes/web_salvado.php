<?php

use App\Http\Controllers\GraficasGeneralesController;
use App\Http\Controllers\GraficasGeneralesMesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneracionDiariaController;
use App\Http\Controllers\CargaMaxController;
use App\Http\Controllers\ControlCargaController;
use App\Http\Controllers\CargaMinController;
use App\Http\Controllers\Reportes;
use App\Http\Controllers\ReportePdfController;
use App\Http\Controllers\GeneracionDiariaEMD;

use App\Http\Controllers\GraficasControlCarga;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dash', function () {
        return view('dash.index');
    })->name('dash');
});

/* Auth::routes(); */

Route::resource('generacion_diaria', GeneracionDiariaController::class); 

route::resource('rep_diario','App\Http\Controllers\Reportes');  



//rutas para grabar informacion diaria
Route::post('/save-data', [GeneracionDiariaController::class, 'saveData'])->name('saveData');
//Route::post('/save-data-EMD', [GeneracionDiariaEMD::class, 'saveDataEMD'])->name('saveDataEMD');


//rutas para el select dinamico
Route::get('/', [GeneracionDiariaController::class, 'index'])->name('index');
Route::get('/get-generadores/{planta_id}', [GeneracionDiariaController::class, 'getGeneradores'])->name('getGeneradores');



//ruta busqueda


Route::get('getPreviousDayData', [GeneracionDiariaController::class, 'getPreviousDayData'])->name('getPreviousDayData');
//actualizar registro diario 
//Route::put('/ruta-actualizar', [GeneracionDiariaController::class, 'actualizar'])->name('regis_diario.actualizar');
//Route::get('actualizar', [GeneracionDiariaController::class, 'actualizar'])->name('actualizar');

Route::post('/actualizar/{gen_maquina_gen_ma_id}', [GeneracionDiariaController::class, 'update'])->name('actualizar');

Route::get('/reportes_pdf/reporte_diario_pdf', [App\Http\Controllers\ReportePdfController::class, 'generarPDF'])->name(name: 'reporte.pdf');

Route::get('/reportes_pdf/reporte_diario_pdf/{fecha}', [App\Http\Controllers\ReportePdfController::class, 'pdf_x_fecha'])->name(name: 'publicaciones.pdf_x_fecha');

//eliminar un registro
//Route::post('/registro/{id}', [App\Http\Controllers\GeneracionDiariaController::class, 'delete'])->name('delete_dia');
Route::post('/ruta-a-tu-controlador/{id}', [GeneracionDiariaController::class, 'delete'])->name('ruta.eliminar');



//rutas para carga maxima 
Route::get('/Carga_maxima', [CargaMaxController::class, 'index'])->name('carga_maxima.crud');
Route::post('/save_carga_maxima', [CargaMaxController::class, 'create'])->name('savecm');
Route::post('/actualizar_cm/{id}', [CargaMaxController::class, 'update'])->name('actualizar_cm');
Route::post('/delete_cm/{id}', [CargaMaxController::class, 'delete'])->name('delete_cm.eliminar');


//rutas para reportes generales o graficas
Route::resource('consulta_general', GraficasGeneralesController::class)->names('consulta_general');

//ruta para busquedas de graficas generales
Route::post('mes', [GraficasGeneralesMesController::class, 'cons_mes'])->name('mes');
//Route::get('/mes', function () {
//    return redirect()->route('dash'); // Redirige al dashboard o a otra página
//});
//Route::post('/consulta_GraficasMes', [App\Http\Controllers\GraficasGeneralesMesController::class, 'cons_mes'])->name('consulta_GraficasMes');

//Route::post('/consulta_GraficasMes', [App\Http\Controllers\GraficasGeneralesController::class, 'cons_mes'])->name('consulta_planta');
//Route::post('/consulta_GraficasMes', [App\Http\Controllers\GraficasGeneralesMesController::class, 'index'])->name('consulta.planta');
//Route::post('consulta/GraficasMes', [App\Http\Controllers\GraficasGeneralesMesController::class, 'index'])->name('consulta.planta');
//Route::resource('consulta_planta', GraficasGeneralesMesController::class)->names('consulta_planta');
//Route::post('consulta_GraficasMes', [App\Http\Controllers\GraficasGeneralesController::class, 'index'])->name('consulta.planta');




//rutas para reportes
Route::get('/reporte-mensual', [Reportes::class, 'reporteMensual'])->name('reporte.mensual');
route::get('reportes/rep_search',[App\Http\Controllers\Reportes::class,'busqueda_rep_d'])->name('reportes.rep_search'); 
route::get('reportes/rep_search_mensual',[App\Http\Controllers\Reportes::class,'busqueda_rep_m'])->name('reportes.rep_search_m');

/* Route::get('/graf_generales_mes', function () {
    return view('dash.graf_generales_mes');
})->name('graf_generales_mes');
 */
Route::get('/graf_generales_mes', function () {
    return view('dash.graf_generales_mes');
})->name('graf_generales_mes');






//rutas para control de carga
Route::get('/control_carga', [ControlCargaController::class, 'index'])->name(' control_carga.crud');
Route::post('registro_cg', [ControlCargaController::class, 'create'])->name('registro_cg');
//Route::post('/save_carga_maxima', [ControlCargaController::class, 'create'])->name('savecm');
//Route::post('/actualizar_cm/{id}', [ControlCargaController::class, 'update'])->name('actualizar_cm');
//Route::post('/delete_cm/{id}', [ControlCargaController::class, 'delete'])->name('delete_cm.eliminar');

Route::post('/save_carga_minima', [CargaMinController::class, 'create'])->name('savecmin');



//rutas para reportes de control de carga
//Route::get('/reporte-mensual', [Reportes::class, 'reporteMensual'])->name('reporte.mensual');
//route::get('reportes/rep_search',[App\Http\Controllers\Reportes::class,'busqueda_rep_d'])->name('reportes.rep_search'); 
//route::get('reportes/rep_search_mensual',[App\Http\Controllers\Reportes::class,'busqueda_rep_m'])->name('reportes.rep_search_m');

//ruta grafica generales para control de carga 
Route::resource('consulta_control_carga', GraficasControlCarga::class)->names('consulta_control_carga');

//Route::get('/consulta_control_cargaa', [GraficasControlCarga::class, 'index'])->name(' consulta_control_cargaa');

//Route::get('/consulta_control_cargaa', [GraficasControlCarga::class, 'index'])->name('reporte');

//Route::get('/consulta_control_carga', function () {
//    return view('dash.graf_control_carga');
//})->name('consulta_control_carga');
//Route::get('/graf_control_carga_mes', function () {
//    return view('dash.graf_control_carga_mes');
//})->name('graf_control_carga_mes');