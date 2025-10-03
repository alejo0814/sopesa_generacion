@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- <script>
 function noTrabajoHoy() {
    // Deshabilitar todos los campos del formulario
    document.querySelectorAll('input').forEach(input => input.disabled = true);
 }


</script> -->
<script>
    function noTrabajoHoy() {
        // Deshabilitar todos los campos del formulario
        document.querySelectorAll('input, select').forEach(input => input.disabled = true);

        // Obtener los valores necesarios para la solicitud
        const fecha = document.querySelector('input[name="fecha"]').value;
        const generador_id = document.querySelector('select[name="generador_id"]').value;
        const planta_id = document.querySelector('select[name="planta_id"]').value;

        // Verificar si los valores existen
        if (!fecha || !generador_id || !planta_id) {
            alert('Por favor, asegúrate de que los campos "fecha", "planta" y "generador" estén llenos.');
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
            return;
        }

        // Hacer una solicitud AJAX para obtener los datos del día anterior
        fetch(`getPreviousDayData?fecha=${fecha}&generador_id=${generador_id}&planta_id=${planta_id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    document.querySelectorAll('input, select').forEach(input => input.disabled = false);
                    return;
                }

                // Llenar los campos del formulario con los datos del día anterior
                document.querySelector('input[name="fecha"]').value = data.fecha_ingresar;
                document.querySelector('input[name="horas_trabajada"]').value = data.gen_reh_hora_trabajada;
                document.querySelector('input[name="horas_disponible"]').value = data.gen_reh_hora_disponible;
                document.querySelector('input[name="lectura_kw"]').value = data.gen_rek_lectura;
                document.querySelector('input[name="lectura_combustible"]').value = data.gen_rec_lectura;
                document.querySelector('input[name="lectura_aceite"]').value = data.gen_rea_lectura;



            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un problema al obtener los datos del día anterior. Por favor, inténtalo de nuevo.');
                document.querySelectorAll('input, select').forEach(input => input.disabled = false);
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
            });
    }

    function overhaull() {
        // Deshabilitar todos los campos del formulario
        document.querySelectorAll('input').forEach(input => input.disabled = true);
        //document.querySelectorAll('select').forEach(input => input.disabled = true);


        // Obtener los valores necesarios para la solicitud
        const fecha = document.querySelector('input[name="fecha"]').value;
        const generador_id = document.querySelector('select[name="generador_id"]').value;
        const planta_id = document.querySelector('select[name="planta_id"]').value;
        const over = document.getElementById('overh').value;

        alert(over);
        // Verificar si los valores existen
        if (!fecha || !generador_id || !planta_id) {
            alert('Por favor, asegúrate de que los campos "fecha", "planta" y "generador" estén llenos.');
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
            return;
        }


        //document.querySelector('input[name="fecha"]').value = 0;
        document.querySelector('input[name="horas_trabajada"]').value = 0;
        document.querySelector('input[name="horas_disponible"]').value = 0;
        document.querySelector('input[name="lectura_kw"]').value = 0;
        document.querySelector('input[name="lectura_combustible"]').value = 0;
        document.querySelector('input[name="lectura_aceite"]').value = 0;

        // Hacer una solicitud AJAX para obtener los datos del día anterior
        // fetch(`getPreviousDayData?fecha=${fecha}&generador_id=${generador_id}&planta_id=${planta_id}`)
        //     .then(response => {
        //         if (!response.ok) {
        //             throw new Error('Network response was not ok');
        //         }
        //         return response.json();
        //     })
        //     .then(data => {
        //         if (data.error) {
        //             alert(data.error);
        //             document.querySelectorAll('input, select').forEach(input => input.disabled = false);
        //             return;
        //         }
        //
        //         // Llenar los campos del formulario con los datos del día anterior
        //         document.querySelector('input[name="fecha"]').value = data.fecha_ingresar;
        //         document.querySelector('input[name="horas_trabajada"]').value = data.gen_reh_hora_trabajada;
        //         document.querySelector('input[name="horas_disponible"]').value = data.gen_reh_hora_disponible;
        //         document.querySelector('input[name="lectura_kw"]').value = data.gen_rek_lectura;
        //         document.querySelector('input[name="lectura_combustible"]').value = data.gen_rec_lectura;
        //         document.querySelector('input[name="lectura_aceite"]').value = data.gen_rea_lectura;
        //
        //
        //
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //         alert('Hubo un problema al obtener los datos del día anterior. Por favor, inténtalo de nuevo.');
        //         document.querySelectorAll('input, select').forEach(input => input.disabled = false);
        //         document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
        //     });
    }
</script>
@stop

@section('content')

<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Datos diarios</h3>
    </div>

    <!-- /.ca  rd-header --> <!-- form start -->
    <form id="dataForm" data-form="planta" method="POST">
        @csrf
        <div class="card-body">
            <!--  <button type="button" onclick="noTrabajoHoy()">No Trabajé Hoy</button> -->
            <div class="row">
                <div class="col-3">
                    <label>Planta Generadora</label>
                    <select class="form-control" id="planta_id" name="planta_id">
                        <option value="">Seleccione una planta</option>

                        @foreach($plantas as $planta)

                        <option value="{{ $planta->gen_pl_id }}">{{ $planta->gen_pl_nombre }}</option>

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

                <div class="col-12">


                    <div class=" col-12 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="noTrabajoHoy()">
                        <label class="custom-control-label" for="customSwitch3">La maquina no trabajó</label>
                    </div>

                    <div class=" col-12 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <input type="checkbox" class="form-control custom-control-input" id="overh" name="overh" onclick="overhaull()" value="1">
                        <label class="custom-control-label" for="overh">Overhaul</label>
                    </div>

                </div>
            </div>
            <div class="row">
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
                <div class="col-4" id="campo_aceite">
                    <label>Lectura aceite</label>
                    <input type="text" class="form-control" id="lectura_aceite" name="lectura_aceite" placeholder="">
                </div>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    <div id="result"></div>
    <!-- <table class="table table-bordered mt-4" id="dataTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Horas trabajada</th>
                <th>Horas disponible</th>
                <th>Lectura Kw</th>
                <th>Consumo Kw</th>
                <th>Lectura combustible</th>
                <th>Consumo combustible</th>
                <th>Lectura aceite</th>
                <th>Consumo aceite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($as as $result_vista)
            <tr>
   


                <td>{{ $result_vista->gen_reh_fecha }}</td>
                <td>{{ $result_vista->gen_reh_hora_trabajada }}</td>
                <td>{{ $result_vista->gen_reh_hora_disponible }}</td>
                <td>{{ $result_vista->gen_rek_lectura }}</td>
                <td>{{ $result_vista->gen_rek_lectura }}</td>
                <td>{{ $result_vista->gen_rec_lectura }}</td>
                <td>{{ $result_vista->gen_rec_consumno }}</td>
                <td>{{ $result_vista->gen_rea_lectura }}</td>
                <td>{{ $result_vista->gen_rea_consumo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table> -->
</div>

<div class="container">

    <!--  <h1>Generación Diaria</h1> -->
    <table class="table" id="example">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Horas Trabajadas</th>
                <th>Horas Disponibles</th>
                <th>Lectura KW</th>
                <th>Lectura Combustible</th>
                <th>Lectura Aceite</th>
                <th>Generación Act</th>
                <th>Consumo Combustible</th>
                <th>Consumo Aceite</th>
                <th>maquina</th>
                <!-- Campo oculto -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($as))
            <tr>
                <td colspan="11">No hay registros disponibles ingresados el dia de hoy.</td>
            </tr>
            @else
            @foreach($as as $registro)
            <tr>
                <td>{{ $registro->gen_reh_fecha }}</td>
                <td>{{ $registro->gen_reh_hora_trabajada }}</td>
                <td>{{ $registro->gen_reh_hora_disponible }}</td>
                <td>{{ $registro->gen_rek_lectura }}</td>
                <td>{{ $registro->gen_rec_lectura }}</td>
                <td>{{ $registro->gen_rea_lectura }}</td>
                <td>{{ $registro->gen_rek_gen_act }}</td>
                <td>{{ $registro->gen_rec_consumno }}</td>
                <td>{{ $registro->gen_rea_consumo }}</td>
                <td>{{ $registro->gen_ma_nombre }}</td>

                <td>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#updateModal"
                        data-fecha="{{ $registro->gen_reh_fecha }}"
                        data-hora-trabajada="{{ $registro->gen_reh_hora_trabajada }}"
                        data-hora-disponible="{{ $registro->gen_reh_hora_disponible }}"
                        data-lectura-kw="{{ $registro->gen_rek_lectura }}"
                        data-lectura-combustible="{{ $registro->gen_rec_lectura }}"
                        data-lectura-aceite="{{ $registro->gen_rea_lectura }}"
                        data-gen-act="{{ $registro->gen_rek_gen_act }}"
                        data-consumo-combustible="{{ $registro->gen_rec_consumno }}"
                        data-consumo-aceite="{{ $registro->gen_rea_consumo }}"
                        data-maquina="{{ $registro->gen_ma_nombre }}"
                        data-maq-id="{{ $registro->gen_maquina_gen_ma_id }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
                        </svg>
                    </button>
                    <button class="btn btn-danger delete-button" data-id="{{ $registro->gen_maquina_gen_ma_id }}" data-fecha="{{ $registro->gen_reh_fecha }}" data-maquina="{{ $registro->gen_ma_nombre }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                        </svg>
                    </button>
                    <!-- Campo oculto -->
                    <input type="hidden" name="num_maquina" value="{{ $registro->gen_maquina_gen_ma_id }}">
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <form id="updateForm" method="POST" action="/actualizar/{{ $registro->gen_maquina_gen_ma_id }}">--}}
            <form id="updateForm" method="POST">
                @csrf
                {{--@method('GET')--}}
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Actualizar Registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="gen_reh_fecha">Fecha</label>
                            <input type="text" class="form-control" id="gen_reh_fecha" name="gen_reh_fecha" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gen_reh_hora_trabajada">Horas Trabajadas</label>
                            <input type="text" class="form-control" id="gen_reh_hora_trabajada" name="gen_reh_hora_trabajada">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gen_reh_hora_disponible">Horas Disponibles</label>
                            <input type="text" class="form-control" id="gen_reh_hora_disponible" name="gen_reh_hora_disponible">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="gen_rek_lectura">Lectura KW</label>
                            <input type="text" class="form-control" id="gen_rek_lectura" name="gen_rek_lectura">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gen_rec_lectura">Lectura Combustible</label>
                            <input type="text" class="form-control" id="gen_rec_lectura" name="gen_rec_lectura">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gen_rea_lectura">Lectura Aceite</label>
                            <input type="text" class="form-control" id="gen_rea_lectura" name="gen_rea_lectura">
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="gen_rek_gen_act">Generación Act</label>
                            <input type="text" class="form-control" id="gen_rek_gen_act" name="gen_rek_gen_act">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gen_rec_consumno">Consumo Combustible</label>
                            <input type="text" class="form-control" id="gen_rec_consumno" name="gen_rec_consumno">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gen_rea_consumo">Consumo Aceite</label>
                            <input type="text" class="form-control" id="gen_rea_consumo" name="gen_rea_consumo">
                        </div>
                        <div class="form-group ">

                            <input type="hidden" class="form-control" id="maq_id" name="maq_id">
                        </div>
                    </div>
                    @if(empty($as))
                    <tr>
                        <input type="hidden" class="form-control" id="num_maquina" name="num_maquina" value="0">
                    </tr>
                    @else
                    <input type="hidden" class="form-control" id="num_maquina" name="num_maquina" value="{{ $registro->gen_maquina_gen_ma_id }}">
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


@stop


@section('css')
{{--<link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>
    $(document).ready(function() {
        $('#updateModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var modal = $(this);

            // Llenar los campos del modal con los datos de la fila
            modal.find('#gen_reh_fecha').val(button.data('fecha'));
            modal.find('#gen_reh_hora_trabajada').val(button.data('hora-trabajada'));
            modal.find('#gen_reh_hora_disponible').val(button.data('hora-disponible'));
            modal.find('#gen_rek_lectura').val(button.data('lectura-kw'));
            modal.find('#gen_rec_lectura').val(button.data('lectura-combustible'));
            modal.find('#gen_rea_lectura').val(button.data('lectura-aceite'));
            modal.find('#gen_rek_gen_act').val(button.data('gen-act'));
            modal.find('#gen_rec_consumno').val(button.data('consumo-combustible'));
            modal.find('#gen_rea_consumo').val(button.data('consumo-aceite'));
            modal.find('#maq_id').val(button.data('maq-id'));
        });
    });
</script>
<script>
    $(document).on('submit', 'form[data-form="planta"]', function(event) {
        event.preventDefault();
        const form = $(this);
        const formId = form.attr('id');

        document.querySelectorAll('input, select').forEach(input => input.disabled = false);

        let url = '';
        let expectedType = '';

        if (formId === 'form_emd') {
            url = '{{ route("saveDataEMD") }}';
            expectedType = 'form_emd';
        } else {
            url = '{{ route("saveData") }}';
            expectedType = 'dataForm';
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Datos guardados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                console.log('Bloque activado:', expectedType);
                console.log(xhr.responseJSON);

                if (xhr.status === 422 && xhr.responseJSON.type === expectedType) {
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
                        html: errorMessage,
                    });
                } else  {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar los datos',
                       // text: xhr.responseJSON.error || 'Ocurrió un error inesperado',
                       text: xhr.responseJSON ? xhr.responseJSON.error : 'Ocurrió un error inesperado',
                    });
                }
            }
        });
    });




    /*  $(document).on('submit', '#form_emd', function(event) {
         //alert('Formulario EMD enviado');
         event.preventDefault();
         document.querySelectorAll('input, select').forEach(input => input.disabled = false);

         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: '{{ route("saveDataEMD") }}',
             method: 'POST',
             data: $(this).serialize(),
             dataType: 'json',
             success: function(response) {
                 Swal.fire({
                     icon: 'success',
                     title: 'Datos guardados correctamente',
                     showConfirmButton: false,
                     timer: 1500
                 }).then(() => {
                     location.reload();
                 });

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

                 console.log('Bloque EMD activado');
                 console.log(xhr.responseJSON);

                 if (xhr.status === 422 && xhr.responseJSON.type === 'form_emd') {
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
                         html: errorMessage,
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
     }); */
</script>
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

        /*   $('#dataForm').on('submit', function(event) {
            event.preventDefault();
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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


                    }).then(() => {
                        location.reload(); // Recargar la página
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

                    console.log('Bloque OTRO activado');
                    console.log(xhr.responseJSON);


                    if (xhr.status === 422 && xhr.responseJSON.type === 'dataForm') {
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



 */



    });
</script>
<script>
    const configuracionesPlanta = {
        '3': {
            label: 'Consumo aceite (gal.) EMD',
            name: 'consumo_aceite_emd',
            formId: 'form_emd'
        },
        // Agrega más configuraciones según tus plantas
    };

    $(document).ready(function() {
        const defaultFormId = 'dataForm';
        const defaultCampoAceite = `
        <label>Lectura aceite</label>
        <input type="text" class="form-control" id="lectura_aceite" name="lectura_aceite" placeholder="">
    `;

        // Selecciona el formulario correcto por su data-form
        const form = document.querySelector('form[data-form="planta"]');
        const campoAceite = document.getElementById('campo_aceite');

        $('#planta_id').on('change', function() {
            const plantaId = this.value;
            const config = configuracionesPlanta[plantaId];

            if (config) {
                campoAceite.innerHTML = `
                <label>${config.label}</label>
                <input type="text" class="form-control" id="${config.name}" name="${config.name}" placeholder="">
            `;
                form.setAttribute('id', config.formId);
            } else {
                campoAceite.innerHTML = defaultCampoAceite;
                form.setAttribute('id', defaultFormId);
            }
        });




    });
</script>


<script>
    $('#updateForm').on('submit', function(event) {
        event.preventDefault();
        document.querySelectorAll('input, select').forEach(input => input.disabled = false);
        document.getElementById('overh').checked = false;

        let form = $(this);



        // let formData = form.serializeArray(); // Serializa los datos del formulario en un arreglo de objetos

        // console.log(formData); // Muestra el arreglo en la consola

        // Si quieres ver los datos en un alert
        // alert(JSON.stringify(formData, null, 2));
        let id = form.find('input[name="maq_id"]').val(); // Obtener el ID del formulario
        //alert(id);
        //let id = form.data('num_maquina'); // Obtener el ID del formulario
        // alert(id);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
             url: (id >= 5 && id <= 10) ? 'actualizar_emd/' + id : 'actualizar/' + id,
            //url: 'actualizar/' + id, // Incluir el ID en la URL
            method: 'post',
           
           data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Datos actualizados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload(); // Recargar la página
                });
                // .then(() => {
                //     $('#miFormulario')[0].reset(); // Limpiar el formulario
                // });
                // Agregar nueva fila a la tabla
                /* $('#dataTable tbody').append(
                    '<tr>' +
                    '<td>' + response.data.fecha + '</td>' +
                    '<td>' + response.data.horas_trabajada + '</td>' +
                    '<td>' + response.data.horas_disponible + '</td>' +
                    '<td>' + response.data.lectura_kw + '</td>' +
                    '<td>' + response.data.lectura_combustible + '</td>' +
                    '<td>' + response.data.lectura_aceite + '</td>' +
                    '</tr>'

                ); */
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

    //    document.addEventListener('DOMContentLoaded', function () {
    //        const deleteButtons = document.querySelectorAll('.delete-button');
    //
    //        deleteButtons.forEach(button => {
    //            button.addEventListener('click', function () {
    //                const id = this.getAttribute('data-id');
    //                const fecha = this.getAttribute('data-fecha');
    //
    //                Swal.fire({
    //                    title: '¿Estás seguro?',
    //                    text: "¡No podrás revertir esto!",
    //                    icon: 'warning',
    //                    showCancelButton: true,
    //                    confirmButtonColor: '#3085d6',
    //                    cancelButtonColor: '#d33',
    //                    confirmButtonText: 'Sí, eliminarlo!',
    //                    cancelButtonText: 'Cancelar'
    //                }).then((result) => {
    //                    if (result.isConfirmed) {
    //                        const url = `/registro/${id}`;
    //                        fetch(url, {
    //                            method: 'POST',
    //                            headers: {
    //                                'Content-Type': 'application/json',
    //                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //                            },
    //                            body: JSON.stringify({ gen_reh_fecha: fecha })
    //                        })
    //                        .then(response => response.json())
    //                        .then(data => {
    //                            if (data.success) {
    //                                Swal.fire(
    //                                    'Eliminado!',
    //                                    'El registro ha sido eliminado.',
    //                                    'success'
    //                                ).then(() => {
    //                                    location.reload();
    //                                });
    //                            } else {
    //                                Swal.fire(
    //                                    'Error!',
    //                                    'Hubo un problema al eliminar el registro.',
    //                                    'error'
    //                                );
    //                            }
    //                        })
    //                        .catch(error => {
    //                            Swal.fire(
    //                                'Error!',
    //                                'Hubo un problema al eliminar el registro.',
    //                                'error'
    //                            );
    //                        });
    //                    }
    //                });
    //            });
    //        });
    //    });




    $(document).ready(function() {
        $('.delete-button').on('click', function() {
            var id = $(this).data('id');
            var fecha = $(this).data('fecha');
            var maquina = $(this).data('maquina');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("ruta.eliminar", ":id") }}'.replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            gen_reh_fecha: fecha,
                            num_maquina: maquina
                        },
                        success: function(response) {
                            Swal.fire(
                                'Eliminado!',
                                response.success,
                                'success'
                            ).then(() => {
                                location.reload(); // Recargar la página
                            });
                            // Aquí puedes agregar código para actualizar la tabla o redirigir
                        },
                        error: function(response) {
                            Swal.fire(
                                'Error!',
                                response.error,
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>


@stop