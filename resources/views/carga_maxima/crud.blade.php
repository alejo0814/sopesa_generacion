@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>SOPESA S.A E.S.P</h1>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">


@stop

@section('content')

    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Carga maxima</h3>
        </div>

        <!-- /.ca  rd-header --> <!-- form start -->
        <form id="form_carga_max" method="POST">
            @csrf
            <div class="card-body">
                <!--  <button type="button" onclick="noTrabajoHoy()">No Trabajé Hoy</button> -->
                <div class="row">
                    <div class="col-3">
                        <label>ciudad</label>
                        <select class="form-control" id="ciudad" name="ciudad">
                            <option value="">Seleccione una sede</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->gen_ci_id }}">{{ $ciudad->gen_ci_nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <label>Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" placeholder="">
                    </div>


                </div>
                <div class="row">
                    <div class="col-4">
                        <label>Carga maxima</label>
                        <input type="number" class="form-control" id="carga_max" name="carga_max" placeholder="">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
        <div id="result"></div>

    </div>

    <div class="container">

        <!--  <h1>Generación Diaria</h1> -->
        <table class="table" id="example">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>valor</th>
                    <th>ciudad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if (empty($table_gen_cm))
                    <tr>
                        <td colspan="11">No hay registros disponibles ingresados el dia de hoy.</td>
                    </tr>
                @else
                    @foreach ($table_gen_cm as $registro)
                        <tr>
                            <td>{{ $registro->gen_cm_fecha }}</td>
                            <td>{{ $registro->gen_cm_value }}</td>
                            <td>{{ $registro->gen_ci_nombre }}</td>
                            <td>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#updateModal"
                                    data-fecha="{{ $registro->gen_cm_fecha }}" data-valor-cm="{{ $registro->gen_cm_value }}"
                                    data-nombre-ciudad="{{ $registro->gen_ci_nombre }}"
                                    data-id="{{ $registro->gen_cm_id }}">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                        <path
                                            d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
                                    </svg>
                                </button>
                                {{-- <button class="btn btn-danger delete-button" data-id="{{ $registro->gen_cm_id }}" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                        </svg>
                    </button> --}}
                                <button class="btn btn-danger delete-button" data-id_cm="{{ $registro->gen_cm_id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5.5 0 0 1-1 0V6a.5.5.5 0 0 1 .5-.5m3 .5a.5.5.5 0 0 0-1 0v6a.5.5.5 0 0 0 1 0z" />
                                        <path
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                    </svg>
                                </button>
                                <!-- Campo oculto -->
                                <input type="hidden" name="id_cm" value="{{ $registro->gen_cm_id }}">
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                {{-- <form id="updateForm_cm" method="POST" action="/actualizar/{{ $registro->gen_maquina_gen_ma_id }}"> --}}
                <form id="updateForm_cm" method="POST">
                    @csrf
                    {{-- @method('GET') --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Actualizar Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="cm_fecha">Fecha</label>
                                <input type="text" class="form-control" id="cm_fecha" name="cm_fecha" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cm_ciudad">Sede</label>
                                <input type="text" class="form-control" id="cm_ciudad" name="cm_ciudad" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cm_valor">Valor Carga Maxima</label>
                                <input type="text" class="form-control" id="cm_valor" name="cm_valor">
                            </div>
                            <input type="hidden" class="form-control" id="cm_id" name="cm_id">
                        </div>



                        {{-- @if (empty($table_gen_cm))
                    <tr>
                        <input class="form-control" id="num_maquina" name="num_maquina" value="0">
                    </tr>
                    @else
                    <input class="form-control" id="num_maquina" name="num_maquina" value="{{ $registro->gen_cm_id }}">
                    @endif --}}

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
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
        new DataTable('#example', {
            order: [
                [0, 'desc']
            ]
        });
        $(document).ready(function() {

            $('#updateModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var modal = $(this);

                // Llenar los campos del modal con los datos de la fila


                modal.find('#cm_fecha').val(button.data('fecha'));
                modal.find('#cm_valor').val(button.data('valor-cm'));
                modal.find('#cm_ciudad').val(button.data('nombre-ciudad'));

                modal.find('#cm_id').val(button.data('id'));
            });
        });
    </script>
    <script>
        $(document).ready(function() {


            $('#form_carga_max').on('submit', function(event) {
                event.preventDefault();
                document.querySelectorAll('input, select').forEach(input => input.disabled = false);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('savecm') }}',
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
                        //$('#dataTable tbody').append(
                        //    '<tr>' +
                        //    '<td>' + response.data.fecha + '</td>' +
                        //    '<td>' + response.data.horas_trabajada + '</td>' +
                        //    '<td>' + response.data.horas_disponible + '</td>' +
                        //    '<td>' + response.data.lectura_kw + '</td>' +
                        //    '<td>' + response.data.lectura_combustible + '</td>' +
                        //    '<td>' + response.data.lectura_aceite + '</td>' +
                        //    '</tr>'



                        //);

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
                                text: xhr.responseJSON ? xhr.responseJSON.error :
                                    'Ocurrió un error inesperado',
                            });
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $('#updateForm_cm').on('submit', function(event) {
            event.preventDefault();
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            //document.getElementById('overh').checked = false;

            let form = $(this);



            // let formData = form.serializeArray(); // Serializa los datos del formulario en un arreglo de objetos

            // console.log(formData); // Muestra el arreglo en la consola

            // Si quieres ver los datos en un alert
            // alert(JSON.stringify(formData, null, 2));
            let id = form.find('input[name="cm_id"]').val(); // Obtener el ID del formulario
            //let id = form.data('num_maquina'); // Obtener el ID del formulario
            // alert(id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'actualizar_cm/' + id, // Incluir el ID en la URL
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
                            text: xhr.responseJSON ? xhr.responseJSON.error :
                                'Ocurrió un error inesperado',
                        });
                    }
                }
            });
        });

        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                var id = $(this).data('id_cm');
                // alert(id);
                // var fecha = $(this).data('fecha');
                // alert(fecha);
                //var maquina = $(this).data('maquina');

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
                            url: '{{ route('delete_cm.eliminar', ':id') }}'.replace(':id',
                                id),
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                //gen_reh_fecha: fecha,
                                //num_maquina: maquina
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
