@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
@stop


@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop


@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Reporte diario-{{ $resul_rep_fecha[0]->gen_repd_fecha ?? 'N/A' }}</h3>
    </div>






    {{--<a href="{{ url('/reportes_pdf/reporte_diario_pdf') }}" class="btn btn-primary">Descargar PDF</a>--}}
    <div class="card-body">
        <form action="{{ route('publicaciones.pdf_x_fecha', ['fecha' => $resul_rep_fecha[0]->gen_repd_fecha ?? 'N/A'])}}" method="get" target="_blank">
            @csrf



            <button class="btn btn-danger" type="submit" >Imprimir PDF</button>


        </form>
        <!-- DataTables CSS -->

        <!-- DataTables JS -->


        @php
        function formatNumber($number) {
        return isset($number) ? number_format($number, 2, '.', ',') : 'N/A';
        }

        @endphp


        <table id="miTabla" class="table table-bordered display compact">
            <thead>
                <tr>
                    <th>PARAMETROS</th>
                    <th>UND</th>
                    <th>{{ $resul_rep_fecha[0]->gen_ma_nombre ?? 'N/A' }}</th>
                    <th>{{ $resul_rep_fecha[1]->gen_ma_nombre ?? 'N/A' }}</th>
                    <th>{{ $resul_rep_fecha[2]->gen_ma_nombre ?? 'N/A' }}</th>
                    <th>{{ $resul_rep_fecha[3]->gen_ma_nombre ?? 'N/A' }}</th>
                    <th>{{ $resul_rep_fecha[4]->gen_ma_nombre ?? 'N/A' }}</th>
                    <th>{{ $resul_rep_fecha[5]->gen_ma_nombre ?? 'N/A' }}</th>
                    <th>PLANTA 2B EMD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CARGA MAXIMA DE CENTRAL</td>
                    <td>kW</td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg, rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg, rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg, rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td>{{ number_format( $cm[0]->gen_cm_value ?? 0)}}</td>

                </tr>
                <tr>
                    <td>GENERACION BRUTA *</td>
                    <td>kWh</td>

                    <td>{{ formatNumber($resul_rep_fecha[0]->gen_repd_gen_bruta ?? null) }}</td>
                    <td>{{ formatNumber($resul_rep_fecha[1]->gen_repd_gen_bruta ?? null)}}</td>
                      <td>{{ formatNumber($resul_rep_fecha[2]->gen_repd_gen_bruta ?? null) }}</td>
                    <td>{{ formatNumber($resul_rep_fecha[3]->gen_repd_gen_bruta ?? null)}}</td>
                      <td>{{ formatNumber($resul_rep_fecha[4]->gen_repd_gen_bruta ?? null) }}</td>
                    <td>{{ formatNumber($resul_rep_fecha[5]->gen_repd_gen_bruta ?? null)}}</td>
                    <td>{{formatNumber( ($resul_rep_fecha[0]->gen_repd_gen_bruta ?? 0) + ($resul_rep_fecha[1]->gen_repd_gen_bruta ?? 0)+ ($resul_rep_fecha[2]->gen_repd_gen_bruta ?? 0)+ ($resul_rep_fecha[3]->gen_repd_gen_bruta ?? 0)+ ($resul_rep_fecha[4]->gen_repd_gen_bruta ?? 0)+ ($resul_rep_fecha[5]->gen_repd_gen_bruta ?? 0)) }}</td>

                <tr>
                    <td>CONSUMO PROPIO</td>
                    <td>kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cons_propio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cons_propio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cons_propio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cons_propio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cons_propio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cons_propio ?? null) }}</td>
                    <td>{{ formatNumber(($resul_rep_fecha[0]->gen_repd_cons_propio ?? 0) + ($resul_rep_fecha[1]->gen_repd_cons_propio ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cons_propio ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cons_propio ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cons_propio ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cons_propio ?? 0)) }}</td>

                </tr>
                <tr>
                    <td>GENERACION NETA</td>
                    <td>kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_gen_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_gen_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_gen_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_gen_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_gen_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_gen_neta ?? null) }}</td>
                    <td>{{ formatNumber(($resul_rep_fecha[0]->gen_repd_gen_neta ?? 0 )+ ($resul_rep_fecha[1]->gen_repd_gen_neta ?? 0)+ ($resul_rep_fecha[2]->gen_repd_gen_neta ?? 0)+ ($resul_rep_fecha[3]->gen_repd_gen_neta ?? 0)+ ($resul_rep_fecha[4]->gen_repd_gen_neta ?? 0)+ ($resul_rep_fecha[5]->gen_repd_gen_neta ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CAPACIDAD NOMINAL</td>
                    <td>kW</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cap_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cap_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cap_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cap_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cap_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cap_nominal ?? null) }}</td>
                    <td>{{ formatNumber(($resul_rep_fecha[0]->gen_repd_cap_nominal ?? 0) + ($resul_rep_fecha[1]->gen_repd_cap_nominal ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cap_nominal ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cap_nominal ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cap_nominal ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cap_nominal ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CAPACIDAD EFFECTIVA</td>
                    <td>kW</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cap_efectiva ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cap_efectiva ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cap_efectiva ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cap_efectiva ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cap_efectiva ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cap_efectiva ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_cap_efectiva ?? 0) +($resul_rep_fecha[1]->gen_repd_cap_efectiva ?? 0)+($resul_rep_fecha[2]->gen_repd_cap_efectiva ?? 0)+($resul_rep_fecha[3]->gen_repd_cap_efectiva ?? 0)+($resul_rep_fecha[4]->gen_repd_cap_efectiva ?? 0)+($resul_rep_fecha[5]->gen_repd_cap_efectiva ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CARGA PROMEDIO</td>
                    <td>kW</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_carg_promedio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_carg_promedio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_carg_promedio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_carg_promedio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_carg_promedio ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_carg_promedio ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_carg_promedio ?? 0) + ($resul_rep_fecha[1]->gen_repd_carg_promedio ?? 0)+ ($resul_rep_fecha[2]->gen_repd_carg_promedio ?? 0)+ ($resul_rep_fecha[3]->gen_repd_carg_promedio ?? 0)+ ($resul_rep_fecha[4]->gen_repd_carg_promedio ?? 0)+ ($resul_rep_fecha[5]->gen_repd_carg_promedio ?? 0))}}</td>

                </tr>
                <tr>
                    <td>INDICE CARGA PROMEDIO NOMINAL</td>
                    <td>%</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_indice_carg_promed_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_indice_carg_promed_nominal ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_indice_carg_promed_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_indice_carg_promed_nominal ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_indice_carg_promed_nominal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_indice_carg_promed_nominal ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_indice_carg_promed_nominal ?? 0) + ($resul_rep_fecha[1]->gen_repd_indice_carg_promed_nominal ?? 0)+ ($resul_rep_fecha[1]->gen_repd_indice_carg_promed_nominal ?? 0)+ ($resul_rep_fecha[1]->gen_repd_indice_carg_promed_nominal ?? 0)+ ($resul_rep_fecha[1]->gen_repd_indice_carg_promed_nominal ?? 0)+ ($resul_rep_fecha[1]->gen_repd_indice_carg_promed_nominal ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CANTIDAD HORAS DE OPERACIÓN *</td>
                    <td>Hrs</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_hrs_operacion ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_hrs_operacion ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_hrs_operacion ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_hrs_operacion ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_hrs_operacion ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_hrs_operacion ?? null) }}</td>
                    
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_hrs_operacion ?? 0) + ($resul_rep_fecha[1]->gen_repd_hrs_operacion ?? 0)+ ($resul_rep_fecha[2]->gen_repd_hrs_operacion ?? 0)+ ($resul_rep_fecha[3]->gen_repd_hrs_operacion ?? 0)+ ($resul_rep_fecha[4]->gen_repd_hrs_operacion ?? 0)+ ($resul_rep_fecha[5]->gen_repd_hrs_operacion ?? 0))}}</td>

                </tr>
                <tr>
                    <td>HORAS DISPONIBLES *</td>
                    <td>Hrs</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_hrs_disponibilidad ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_hrs_disponibilidad ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_hrs_disponibilidad ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_hrs_disponibilidad ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_hrs_disponibilidad ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_hrs_disponibilidad ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_hrs_disponibilidad ?? 0) + ($resul_rep_fecha[1]->gen_repd_hrs_disponibilidad ?? 0)+ ($resul_rep_fecha[2]->gen_repd_hrs_disponibilidad ?? 0)+ ($resul_rep_fecha[3]->gen_repd_hrs_disponibilidad ?? 0)+ ($resul_rep_fecha[4]->gen_repd_hrs_disponibilidad ?? 0)+ ($resul_rep_fecha[5]->gen_repd_hrs_disponibilidad ?? 0))}}</td>

                </tr>
                <tr>
                    <td>DISPONIBILIDAD GENERADORES</td>
                    <td>%</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_disp_generador ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_disp_generador ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_disp_generador ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_disp_generador ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_disp_generador ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_disp_generador ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_disp_generador ?? 0) + ($resul_rep_fecha[1]->gen_repd_disp_generador ?? 0)+ ($resul_rep_fecha[2]->gen_repd_disp_generador ?? 0)+ ($resul_rep_fecha[3]->gen_repd_disp_generador ?? 0)+ ($resul_rep_fecha[4]->gen_repd_disp_generador ?? 0)+ ($resul_rep_fecha[5]->gen_repd_disp_generador ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CONSUMO COMBUSTIBLE</td>
                    <td>Lts</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cons_combustible_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cons_combustible_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cons_combustible_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cons_combustible_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cons_combustible_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cons_combustible_lts ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_cons_combustible_lts ?? 0) + ($resul_rep_fecha[1]->gen_repd_cons_combustible_lts ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cons_combustible_lts ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cons_combustible_lts ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cons_combustible_lts ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cons_combustible_lts ?? 0))}}</td>
                    <!-- <td>{{ formatNumber( $data->consumo_combustible_lts_man_01 ?? 0 ?? null) }}</td> -->
                    <!-- <td>{{ formatNumber( $data->consumo_combustible_lts_man_02 ?? 0 ?? null) }}</td> -->
                    <!-- <td>{{ formatNumber( $data->consumo_combustible_lts_planta ?? 0 ?? null) }}</td> -->
                </tr>
                <tr>
                    <td>CONSUMO COMBUSTIBLE *</td>
                    <td>gal</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cons_combustible_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cons_combustible_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cons_combustible_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cons_combustible_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cons_combustible_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cons_combustible_gal ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_cons_combustible_gal ?? 0) + ($resul_rep_fecha[1]->gen_repd_cons_combustible_gal ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cons_combustible_gal ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cons_combustible_gal ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cons_combustible_gal ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cons_combustible_gal ?? 0))}}</td>
                    <!--   <td>{{ formatNumber( $data->consumo_combustible_gal_man_01 ?? 0 ?? null) }}</td> -->
                    <!--   <td>{{ formatNumber( $data->consumo_combustible_gal_man_02 ?? 0 ?? null) }}</td> -->
                    <!--   <td>{{ formatNumber( $data->consumo_combustible_gal_planta ?? 0 ?? null) }}</td> -->
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_efi_comb_bruta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_efi_comb_bruta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_efi_comb_bruta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_efi_comb_bruta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_efi_comb_bruta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_efi_comb_bruta ?? null) }}</td>
                    
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_efi_comb_bruta ?? 0) + ($resul_rep_fecha[1]->gen_repd_efi_comb_bruta ?? 0)+ ($resul_rep_fecha[2]->gen_repd_efi_comb_bruta ?? 0)+ ($resul_rep_fecha[3]->gen_repd_efi_comb_bruta ?? 0)+ ($resul_rep_fecha[4]->gen_repd_efi_comb_bruta ?? 0)+ ($resul_rep_fecha[5]->gen_repd_efi_comb_bruta ?? 0))}}</td>

                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE NETA</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_efi_comb_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_efi_comb_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_efi_comb_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_efi_comb_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_efi_comb_neta ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_efi_comb_neta ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_efi_comb_neta ?? 0) + ($resul_rep_fecha[1]->gen_repd_efi_comb_neta ?? 0)+ ($resul_rep_fecha[2]->gen_repd_efi_comb_neta ?? 0)+ ($resul_rep_fecha[3]->gen_repd_efi_comb_neta ?? 0)+ ($resul_rep_fecha[4]->gen_repd_efi_comb_neta ?? 0)+ ($resul_rep_fecha[5]->gen_repd_efi_comb_neta ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CONSUMO COMB. ESPECIFICO BRUTO</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_con_comb_esp_bruto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_con_comb_esp_bruto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_con_comb_esp_bruto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_con_comb_esp_bruto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_con_comb_esp_bruto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_con_comb_esp_bruto ?? null) }}</td>
                    <td>{{formatNumber( ($resul_rep_fecha[0]->gen_repd_con_comb_esp_bruto ?? 0) + ($resul_rep_fecha[1]->gen_repd_con_comb_esp_bruto ?? 0)+ ($resul_rep_fecha[2]->gen_repd_con_comb_esp_bruto ?? 0)+ ($resul_rep_fecha[3]->gen_repd_con_comb_esp_bruto ?? 0)+ ($resul_rep_fecha[4]->gen_repd_con_comb_esp_bruto ?? 0)+ ($resul_rep_fecha[5]->gen_repd_con_comb_esp_bruto ?? 0))}}</td>
                </tr>
                <tr>
                    <td>CONSUMO COMB. ESPECIFICO NETO</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cons_comb_esp_neto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cons_comb_esp_neto ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cons_comb_esp_neto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cons_comb_esp_neto ?? null) }}</td>
                     <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cons_comb_esp_neto ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cons_comb_esp_neto ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_cons_comb_esp_neto ?? 0) + ($resul_rep_fecha[1]->gen_repd_cons_comb_esp_neto ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cons_comb_esp_neto ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cons_comb_esp_neto ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cons_comb_esp_neto ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cons_comb_esp_neto ?? 0))}}</td>
                </tr>
                <tr>
                    <td>EXISTENCIA ACEITE LUBR. TQ ALMACEN. *</td>
                    <td>gal/kWh</td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>
                    <td style="background: repeating-linear-gradient(  45deg,rgba(74, 94, 131, 0.37), rgba(74, 94, 131, 0.37) 10px,  #fff 10px,  #fff 20px);"> </td>


                </tr>
                <tr>
                    <td>CONSUMO ACEITE *</td>
                    <td>gal</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cons_aceite_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cons_aceite_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cons_aceite_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cons_aceite_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cons_aceite_gal ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cons_aceite_gal ?? null) }}</td>
                    
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_cons_aceite_gal ?? 0) + ($resul_rep_fecha[1]->gen_repd_cons_aceite_gal ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cons_aceite_gal ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cons_aceite_gal ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cons_aceite_gal ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cons_aceite_gal ?? 0))}}</td>

                </tr>
                <tr>
                    <td>CONSUMO ACEITE</td>
                    <td>lts</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_cons_aceite_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_cons_aceite_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_cons_aceite_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_cons_aceite_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_cons_aceite_lts ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_cons_aceite_lts ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_cons_aceite_lts ?? 0) + ($resul_rep_fecha[1]->gen_repd_cons_aceite_lts ?? 0)+ ($resul_rep_fecha[2]->gen_repd_cons_aceite_lts ?? 0)+ ($resul_rep_fecha[3]->gen_repd_cons_aceite_lts ?? 0)+ ($resul_rep_fecha[4]->gen_repd_cons_aceite_lts ?? 0)+ ($resul_rep_fecha[5]->gen_repd_cons_aceite_lts ?? 0))}}</td>

                </tr>
                <tr>
                    <td>HORAS TRABAJO MOTOR & T/C *</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_hrs_trab_motor_tc ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_hrs_trab_motor_tc ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_hrs_trab_motor_tc ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_hrs_trab_motor_tc ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_hrs_trab_motor_tc ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_hrs_trab_motor_tc ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_hrs_trab_motor_tc ?? 0) + ($resul_rep_fecha[1]->gen_repd_hrs_trab_motor_tc ?? 0)+ ($resul_rep_fecha[2]->gen_repd_hrs_trab_motor_tc ?? 0)+ ($resul_rep_fecha[3]->gen_repd_hrs_trab_motor_tc ?? 0)+ ($resul_rep_fecha[4]->gen_repd_hrs_trab_motor_tc ?? 0)+ ($resul_rep_fecha[5]->gen_repd_hrs_trab_motor_tc ?? 0))}}</td>

                </tr>
                <tr>
                    <td>HORAS DEL ULTIMO OVERHAUL</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_hrs_last_overhaul ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_hrs_last_overhaul ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_hrs_last_overhaul ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_hrs_last_overhaul ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_hrs_last_overhaul ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_hrs_last_overhaul ?? null) }}</td>
                    <td>{{formatNumber( ($resul_rep_fecha[0]->gen_repd_hrs_last_overhaul ?? 0) + ($resul_rep_fecha[1]->gen_repd_hrs_last_overhaul ?? 0)+ ($resul_rep_fecha[2]->gen_repd_hrs_last_overhaul ?? 0)+ ($resul_rep_fecha[4]->gen_repd_hrs_last_overhaul ?? 0)+ ($resul_rep_fecha[3]->gen_repd_hrs_last_overhaul ?? 0)+ ($resul_rep_fecha[5]->gen_repd_hrs_last_overhaul ?? 0))}}</td>

                </tr>
                <tr>
                    <td>HORAS DESPUES ULTIMO MANT-TO</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_hrs_last_mantenimiento ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_hrs_last_mantenimiento ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_hrs_last_mantenimiento ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_hrs_last_mantenimiento ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_hrs_last_mantenimiento ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_hrs_last_mantenimiento ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_hrs_last_mantenimiento ?? 0) + ($resul_rep_fecha[1]->gen_repd_hrs_last_mantenimiento ?? 0)+ ($resul_rep_fecha[2]->gen_repd_hrs_last_mantenimiento ?? 0)+ ($resul_rep_fecha[3]->gen_repd_hrs_last_mantenimiento ?? 0)+ ($resul_rep_fecha[4]->gen_repd_hrs_last_mantenimiento ?? 0)+ ($resul_rep_fecha[5]->gen_repd_hrs_last_mantenimiento ?? 0))}}</td>

                </tr>
                <tr>
                    <td>HORAS TRABAJADAS ACEITE LUBR. MOTOR *</td>
                    <td>gal/kWh</td>
                    <td>{{ formatNumber( $resul_rep_fecha[0]->gen_repd_hrs_trab_ace_lub_motor ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[1]->gen_repd_hrs_trab_ace_lub_motor ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[2]->gen_repd_hrs_trab_ace_lub_motor ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[3]->gen_repd_hrs_trab_ace_lub_motor ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[4]->gen_repd_hrs_trab_ace_lub_motor ?? null) }}</td>
                    <td>{{ formatNumber( $resul_rep_fecha[5]->gen_repd_hrs_trab_ace_lub_motor ?? null) }}</td>
                    <td>{{ formatNumber( ($resul_rep_fecha[0]->gen_repd_hrs_trab_ace_lub_motor ?? 0) + ($resul_rep_fecha[1]->gen_repd_hrs_trab_ace_lub_motor ?? 0)+ ($resul_rep_fecha[2]->gen_repd_hrs_trab_ace_lub_motor ?? 0)+ ($resul_rep_fecha[3]->gen_repd_hrs_trab_ace_lub_motor ?? 0)+ ($resul_rep_fecha[4]->gen_repd_hrs_trab_ace_lub_motor ?? 0)+ ($resul_rep_fecha[5]->gen_repd_hrs_trab_ace_lub_motor ?? 0))}}</td>

                </tr>
            </tbody>
        </table>

    </div>
</div>


@stop


@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#miTabla').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "pageLength": 30, // Número predeterminado de registros por página
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ] // Opciones en el menú desplegable
        });
    });
</script>
@stop