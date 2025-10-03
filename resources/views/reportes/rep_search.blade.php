@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
<!-- <script>
    function noTrabajoHoy() {
    // Deshabilitar todos los campos del formulario
    document.querySelectorAll('input').forEach(input => input.disabled = true);

    // Obtener los valores necesarios para la solicitud
    const fecha = document.querySelector('input[name="fecha"]').value;
   // const generador_id = document.querySelector('input[name="generador_id"]').value;
    const generador_id = 1;

    // Hacer una solicitud AJAX para obtener los datos del día anterior
    fetch(`/getPreviousDayData?fecha=${fecha}&generador_id=${generador_id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            // Llenar los campos del formulario con los datos del día anterior
            document.querySelector('input[name="fecha"]').value = data.fecha;
            document.querySelector('input[name="generador_id"]').value = data.generador_id;
            document.querySelector('input[name="planta_id"]').value = data.planta_id;
            document.querySelector('input[name="horas_trabajada"]').value = data.horas_trabajada;
            document.querySelector('input[name="horas_disponible"]').value = data.horas_disponible;
            document.querySelector('input[name="lectura_kw"]').value = data.lectura_kw;
            document.querySelector('input[name="lectura_combustible"]').value = data.lectura_combustible;
            document.querySelector('input[name="lectura_aceite"]').value = data.lectura_aceite;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al obtener los datos del día anterior. Por favor, inténtalo de nuevo.');
        });
}
</script> -->
@stop

@section('content')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Consulta reporte diario</h3>
    </div>
    <a href="{{ url('/reportes_pdf/reporte_diario_pdf') }}" class="btn btn-primary">Descargar PDF</a>
    <!-- /.card-header -->
    <!-- form start -->

    <form id="dataForm" method="POST">
        @csrf
        <div class="card-body">
            <!-- <button type="button" onclick="noTrabajoHoy()">No Trabajé Hoy</button> -->
            <div class="row">
                <div class="col-3">
                    <label>Planta Generadora</label>
                    <select class="form-control" id="planta_id" name="planta_id">
                        <option value="">Seleccione una planta</option>
                        @foreach($plantas as $planta)
                        <option value="{{ $planta->gen_pl_id  }}">{{ $planta->gen_pl_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label>Generador</label>
                    <select class="form-control" id="generador_id" name="generador_id">
                        <option value="">Seleccione un generador</option>
                    </select>
                </div>
                <div class="col-3">
                    <label>Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" placeholder="">
                </div>

            </div>
           
            <!--  <div class="row">
                <div class="col-4">
                    <label>Horas trabajada</label>
                    <input type="text" class="form-control" id="horas_trabajada" name="horas_trabajada" placeholder="">
                </div>
                <div class="col-5">
                    <label>Horas disponible</label>
                    <input type="text" class="form-control" id="horas_disponible" name="horas_disponible" placeholder="">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label>Lectura Kw</label>
                    <input type="text" class="form-control" id="lectura_kw" name="lectura_kw" placeholder="">
                </div>
                <div class="col-4">
                    <label>Lectura combustible</label>
                    <input type="text" class="form-control" id="lectura_combustible" name="lectura_combustible" placeholder="">
                </div>
                <div class="col-4">
                    <label>Lectura aceite</label>
                    <input type="text" class="form-control" id="lectura_aceite" name="lectura_aceite" placeholder="">
                </div> -->
        </div><div class="card-footer">
    <button type="submit" class="btn btn-primary">Buscar</button>
</div>
</div>
<!-- /.card-body -->


</form>
<div id="result">
    <div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Reporte diario</h3>
    </div>
    <div class="card-body">

       <!-- DataTables CSS -->
      
        <!-- DataTables JS -->
       



        <table id="miTabla" class="table table-bordered display compact">
            <thead>
                <tr>
                    <th>PARAMETROS</th>
                    <th>UND</th>
                    <th>MAN 01</th>
                    <th>MAN 02</th>
                    <th>PLANTA 3D MAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CARGA MAXIMA DE CENTRAL</td>
                    <td>kW</td>
                    <!-- <td>{{ $data->carga_maxima_central_man_01 ?? '' }}</td> -->
                    <!-- <td>{{ $data->carga_maxima_central_man_02 ?? '' }}</td> -->
                    <!-- <td>{{ $data->carga_maxima_central_planta ?? '' }}</td> -->
                </tr>
                <tr>
                    <td>GENERACION BRUTA *</td>
                    <td>kWh</td>
                     <!-- <td>{{ $data1[2]->gen_repd_gen_bruta}}</td> -->
                    <!--  <td>{{ $data->generacion_bruta_man_02 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->generacion_bruta_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>CONSUMO PROPIO</td>
                    <td>kWh</td>
                    <!-- <td>{{ $data->consumo_propio_man_01 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->consumo_propio_man_02 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->consumo_propio_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>GENERACION NETA</td>
                    <td>kWh</td>
                    <!-- <td>{{ $data->generacion_neta_man_01 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->generacion_neta_man_02 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->generacion_neta_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>CAPACIDAD NOMINAL</td>
                    <td>kW</td>
                    <!--   <td>{{ $data->capacidad_nominal_man_01 ?? 0 }}</td> -->
                    <!--   <td>{{ $data->capacidad_nominal_man_02 ?? 0 }}</td> -->
                    <!--   <td>{{ $data->capacidad_nominal_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>CAPACIDAD EFFECTIVA</td>
                    <td>kW</td>
                    <!--  <td>{{ $data->capacidad_efectiva_man_01 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->capacidad_efectiva_man_02 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->capacidad_efectiva_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>CARGA PROMEDIO</td>
                    <td>kW</td>
                    <!-- <td>{{ $data->carga_promedio_man_01 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->carga_promedio_man_02 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->carga_promedio_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>INDICE CARGA PROMEDIO NOMINAL</td>
                    <td>%</td>
                    <!--  <td>{{ $data->indice_carga_promedio_nominal_man_01 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->indice_carga_promedio_nominal_man_02 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->indice_carga_promedio_nominal_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>CANTIDAD HORAS DE OPERACIÓN *</td>
                    <td>Hrs</td>
                    <!-- <td>{{ $data->horas_operacion_man_01 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->horas_operacion_man_02 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->horas_operacion_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>HORAS DISPONIBLES *</td>
                    <td>Hrs</td>
                    <!--  <td>{{ $data->horas_disponibles_man_01 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->horas_disponibles_man_02 ?? 0 }}</td> -->
                    <!--  <td>{{ $data->horas_disponibles_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>DISPONIBILIDAD GENERADORES</td>
                    <td>%</td>
                    <!--   <td>{{ $data->disponibilidad_generadores_man_01 ?? 0 }}</td> -->
                    <!--   <td>{{ $data->disponibilidad_generadores_man_02 ?? 0 }}</td> -->
                    <!--   <td>{{ $data->disponibilidad_generadores_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>CONSUMO COMBUSTIBLE</td>
                    <td>Lts</td>
                    <!-- <td>{{ $data->consumo_combustible_lts_man_01 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->consumo_combustible_lts_man_02 ?? 0 }}</td> -->
                    <!-- <td>{{ $data->consumo_combustible_lts_planta ?? 0 }}</td> -->
                    </tr<!--> -->
                <tr>
                    <td>CONSUMO COMBUSTIBLE *</td>
                    <td>gal</td>
                    <!--   <td>{{ $data->consumo_combustible_gal_man_01 ?? 0 }}</td> -->
                    <!--   <td>{{ $data->consumo_combustible_gal_man_02 ?? 0 }}</td> -->
                    <!--   <td>{{ $data->consumo_combustible_gal_planta ?? 0 }}</td> -->
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
                <tr>
                    <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                    <td>gal/kWh</td>
                    
                </tr>
            </tbody>
        </table>

    </div>
</div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#planta_id').on('change', function() {
            var planta_id = $(this).val();
            if (planta_id) {
                $.ajax({
                    url: '{{ url("/get-generadores") }}/' + planta_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#generador_id').empty();
                        $('#generador_id').append('<option value="">Seleccione un generador</option>');
                        $.each(data, function(key, value) {
                            $('#generador_id').append('<option value="' + value.gen_ma_id + '">' + value.gen_ma_nombre + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#generador_id').empty();
                $('#generador_id').append('<option value="">Seleccione un generador</option>');
            }
        });

        $('#dataForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route("saveData") }}',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Datos guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Agregar nueva fila a la tabla
                    $('#dataTable tbody').append(
                        '<tr>' +
                        '<td>' + response.data.fecha + '</td>' +
                        '<td>' + response.data.horas_trabajada + '</td>' +
                        '<td>' + response.data.horas_disponible + '</td>' +
                        '<td>' + response.data.lectura_kw + '</td>' +
                        '<td>' + response.data.lectura_combustible + '</td>' +
                        '<td>' + response.data.lectura_aceite + '</td>' +
                        '</tr>'
                    );
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.messages;
                        let errorMessage = xhr.responseJSON.error || 'Error de validación ';
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += errors[field].join('<br>') + '<br>';
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage, // Cambiado a 'html' para permitir saltos de línea
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar los datos',
                            text: xhr.responseJSON ? xhr.responseJSON.error : 'Ocurrió un error inesperado',
                        });
                    }
                }
            });
        });
    });
</script>

@stop