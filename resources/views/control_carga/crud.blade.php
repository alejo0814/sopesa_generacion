@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f0f0f0;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    @media screen and (max-width: 600px) {
        table {
            border: 0;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 10px;
            display: block;
            border-bottom: 2px solid #ddd;
        }

        td {
            display: block;
            text-align: right;
            font-size: 0.8em;
            border-bottom: 1px solid #ddd;
        }

        td:before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }
    }
</style>


@stop

@section('content')

{{--<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Consulta reporte diario</h3>
    </div>--}}

<div class="card card-secondary">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title">Control Carga</h3>
        <form class="d-flex ml-auto" role="search" id="buscar_control_carga_dia" action="{{ route('buscar_control_carga_dia') }}" method="POST">
            @csrf
            <input id="fecha_buscar_c_c" name="fecha_buscar_c_c" class="form-control me-2" type="date" placeholder="" aria-label="" />
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>

    <!-- /.ca  rd-header --> <!-- form start -->

    <div class="card-body">
        <!--  <button type="button" onclick="noTrabajoHoy()">No Trabajé Hoy</button> -->
        <div class="row">
            <div class="col-4">
                <button class="btn btn-primary" data-toggle="modal" data-target="#nuevoregistromodal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                    Nuevo Registro
                </button>

                <!--  <select class="form-control" id="ciudad" name="ciudad">
                        <option value="">Seleccione una sede</option>
                        @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->gen_ci_id }}">{{ $ciudad->gen_ci_nombre}}</option>
                        @endforeach
                    </select> -->
            </div>

            <div class="col-4">
                <label></label>

                <button class="btn btn-primary" data-toggle="modal" data-target="#cargamaximamodal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5" />
                    </svg>
                    carga maxima
                </button>
            </div>
            <div class="col-4">
                <button class="btn btn-primary" data-toggle="modal" data-target="#cargaminimamodal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-down-arrow" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 11.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-1 0v2.6l-3.613-4.417a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61L13.445 11H10.5a.5.5 0 0 0-.5.5" />
                    </svg>
                    carga minima
                </button>
            </div>


        </div>

    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
    </div>

    <div id="result"></div>

<div class="container">
</div>
<div class="table-container">
    <table id="table_control_carga" class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Hora</th>
                <th>MB 01</th>
               {{--  <th>qq</th> --}}

                <th>MB 02</th>
                <th>EMD 09</th>
                <th>EMD 10</th>
                <th>EMD 11</th>
                <th>EMD 12</th>
                <th>EMD 13</th>
                <th>EMD 14</th>
                <th>MAN 01</th>
                <th>MAN 02</th>
                <th>TOTAL (KW)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabla as $hora => $valores)
            <tr>
                <td>{{ $hora }}:00</td>
                <td>{{ $valores['MB 01'] ?? '~' }}</td>
                {{-- <td>{{ $valores['ID MB 01'] ?? '~' }}</td> --}}

                <td>{{ $valores['MB 02'] ?? '~' }}</td>
                <td>{{ $valores['EMD 09'] ?? '~' }}</td>
                <td>{{ $valores['EMD 10'] ?? '~' }}</td>
                <td>{{ $valores['EMD 11'] ?? '~' }}</td>
                <td>{{ $valores['EMD 12'] ?? '~' }}</td>
                <td>{{ $valores['EMD 13'] ?? '~' }}</td>
                <td>{{ $valores['EMD 14'] ?? '~' }}</td>
                <td>{{ $valores['MAN 01'] ?? '~' }}</td>
                <td>{{ $valores['MAN 02'] ?? '~' }}</td>
                <td>{{ $valores['TOTAL (KW)'] }}</td>
                <td> <button class="btn btn-primary" data-toggle="modal" data-target="#DataEdit"
                        data-mb1="{{ $valores['MB 01'] ?? '' }}"
                        data-id-mb1="{{ $valores['ID MB 01'] ?? '' }}"
                        data-mb2="{{ $valores['MB 02'] ?? '' }}"
                        data-id-mb2="{{ $valores['ID MB 02'] ?? '' }}"
                        data-emd09="{{ $valores['EMD 09'] ?? '' }}"
                        data-id-emd09="{{ $valores['ID EMD 09'] ?? '' }}"
                        data-emd10="{{ $valores['EMD 10'] ?? '' }}"
                        data-id-emd10="{{ $valores['ID EMD 10'] ?? '' }}"
                        data-emd11="{{ $valores['EMD 11'] ?? '' }}"
                        data-id-emd11="{{ $valores['ID EMD 11'] ?? '' }}"
                        data-emd12="{{ $valores['EMD 12'] ?? '' }}"
                        data-id-emd12="{{ $valores['ID EMD 12'] ?? '' }}"
                        data-emd13="{{ $valores['EMD 13'] ?? '' }}"
                        data-id-emd13="{{ $valores['ID EMD 13'] ?? '' }}"
                        data-emd14="{{ $valores['EMD 14'] ?? '' }}"
                        data-id-emd14="{{ $valores['ID EMD 14'] ?? '' }}"
                        data-man1="{{ $valores['MAN 01'] ?? '' }}"
                        data-id-man1="{{ $valores['ID MAN 01']  ?? '' }}"
                        data-man2="{{ $valores['MAN 02'] ?? '' }}"
                        data-id-man2="{{ $valores['ID MAN 02']  ?? '' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
                        </svg>
                    </button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">POTENCIA MAXIMA GENERADA (KW)</th>
                    <th scope="col">HORA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="POTENCIA MAXIMA GENERADA (KW)">{{ $carga_max_hoy[0]->gen_cm_value ?? '~'}}</td>
                    <td data-label="HORA">{{ $carga_max_hoy[0]->gen_cm_hora ?? '~'}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">POTENCIA MINIMA GENERADA (KW)</th>
                    <th scope="col">HORA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="POTENCIA MINIMA GENERADA (KW)">{{ $carga_min_hoy[0]->gen_cmi_value ?? '~' }}</td>
                    <td data-label="HORA">{{ $carga_min_hoy[0]->gen_cmi_hora ?? '~'}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
{{--
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
            @if(empty($table_gen_cm))
            <tr>
                <td colspan="11">No hay registros disponibles ingresados el dia de hoy.</td>
            </tr>
            @else
            @foreach($table_gen_cm as $registro)
            <tr>
                <td>{{ $registro->gen_cm_fecha }}</td>
<td>{{ $registro->gen_cm_value }}</td>
<td>{{ $registro->gen_ci_nombre  }}</td>
<td>
    <button class="btn btn-primary" data-toggle="modal" data-target="#updateModal"
        data-fecha="{{ $registro->gen_cm_fecha }}"
        data-valor-cm="{{ $registro->gen_cm_value }}"
        data-nombre-ciudad="{{ $registro->gen_ci_nombre }}"
        data-id="{{ $registro->gen_cm_id  }}">

        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
        </svg>
    </button>

    <button class="btn btn-danger delete-button" data-id_cm="{{ $registro->gen_cm_id }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5.5 0 0 1-1 0V6a.5.5.5 0 0 1 .5-.5m3 .5a.5.5.5 0 0 0-1 0v6a.5.5.5 0 0 0 1 0z" />
            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
        </svg>
    </button>
    <!-- Campo oculto 
                    <input type="hidden" name="id_cm" value="{{ $registro->gen_cm_id }}">-->
</td>
</tr>
@endforeach
@endif
</tbody>
</table>--}}
</div>
<!-- Modal para el nuevo registro dee control de carga  -->
<div class="modal fade" id="nuevoregistromodal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <form id="updateForm_cm" method="POST" action="/actualizar/{{ $registro->gen_maquina_gen_ma_id }}">--}}
            <form id="registro_cg" method="POST">
                @csrf
                {{--@method('GET')--}}
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Nuevo registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cg_fecha">Fecha</label>
                            <input type="date" class="form-control" id="cg_fecha" name="cg_fecha">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Selecciona una Hora</label>
                            <select class="form-control" id="cg_hora" name="cg_hora">
                                <option value="00:00">00:00</option>
                                <option value="01:00">01:00</option>
                                <option value="02:00">02:00</option>
                                <option value="03:00">03:00</option>
                                <option value="04:00">04:00</option>
                                <option value="05:00">05:00</option>
                                <option value="06:00">06:00</option>
                                <option value="07:00">07:00</option>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                                <option value="17:00">17:00</option>
                                <option value="18:00">18:00</option>
                                <option value="19:00">19:00</option>
                                <option value="20:00">20:00</option>
                                <option value="21:00">21:00</option>
                                <option value="22:00">22:00</option>
                                <option value="23:00">23:00</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Planta Generadora</label>
                            <select class="form-control" id="cg_generador" name="cg_generador">
                                <option value="">Seleccione una planta</option>
                                @foreach($generadores as $generador)
                                <option value="{{ $generador->gen_ma_id }}">{{ $generador->gen_ma_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cm_valor">carga kw</label>
                            <input type="number" class="form-control" id="cg_valor" name="cg_valor">
                        </div>
                        <input type="hidden" class="form-control" id="cm_id" name="cm_id">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar datos</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="cargamaximamodal" tabindex="-1" role="dialog" aria-labelledby="cargamaximamodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <form id="updateForm_cm" method="POST" action="/actualizar/{{ $registro->gen_maquina_gen_ma_id }}">--}}
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Nuevo registro de carga maxima</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="registro_cm" method="POST">
                @csrf
                <div class="card-body">
                    <!--  <button type="button" onclick="noTrabajoHoy()">No Trabajé Hoy</button> -->
                    <div class="row">
                        <div class="col-3">
                            <label>ciudad</label>
                            <select class="form-control" id="ciudad" name="ciudad">
                                <option value="">Seleccione una sede</option>
                                @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->gen_ci_id }}">{{ $ciudad->gen_ci_nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" placeholder="">
                        </div>
                        <div class="col-3">
                            <label>hora</label>
                            <input type="number" class="form-control" id="hora" name="hora" placeholder="">
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

        </div>
    </div>
</div>
<div class="modal fade" id="cargaminimamodal" tabindex="-1" role="dialog" aria-labelledby="cargaminimamodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <form id="updateForm_cm" method="POST" action="/actualizar/{{ $registro->gen_maquina_gen_ma_id }}">--}}
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Nuevo registro de carga minima</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="registro_cmin" method="POST">
                @csrf
                <div class="card-body">
                    <!--  <button type="button" onclick="noTrabajoHoy()">No Trabajé Hoy</button> -->
                    <div class="row">
                        <div class="col-3">
                            <label>ciudad</label>
                            <select class="form-control" id="ciudad" name="ciudad">
                                <option value="">Seleccione una sede</option>
                                @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->gen_ci_id }}">{{ $ciudad->gen_ci_nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" placeholder="">
                        </div>
                        <div class="col-sm-2">
                            <label>hora</label>
                           
                             <input type="number" class="form-control" id="hora" name="hora" placeholder="">
                        </div>
                         <div class="col-sm-2">
                            <label>hora</label>
                            <input type="number" class="form-control" id="hora" name="hora" placeholder="">
                           
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

        </div>
    </div>
</div>




{{-- modal para editar los campos--}}
<div class="modal fade" id="DataEdit" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <form id="updateForm_cm" method="POST" action="/actualizar/{{ $registro->gen_maquina_gen_ma_id }}">--}}
            <form id="updateForm_control_carga" method="POST">
                @csrf
                {{--@method('GET')--}}
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Actualizar Registro de control de carga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cm_fecha">Fecha</label>
                            <input type="text" class="form-control" id="cm_fecha" name="cm_fecha">
                        </div>
                        <div class="form-group col-md-3 registro" data-campo="data_mb1"
                            <label for="data_mb1">MB 1</label>
                            <input type="text" class="form-control" id="data_mb1" name="data_mb1">
                            <input type="text" class="hidden" id="data-id-mb1" name="data-id-mb1" hidden>
                        </div>
                        <div class="form-group col-md-3 registro" data-campo="data_mb2">
                            <label for="data_mb1">MB 2</label>
                            <input type="text" class="form-control" id="data_mb2" name="data_mb2">
                            <input type="text" class="hidden" id="data-id-mb2" name="data-id-mb2" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_emd09">
                            <label for="data_emd09">EMD 09</label>
                            <input type="text" class="form-control" id="data_emd09" name="data_emd09">
                            <input type="text" class="hidden" id="data-id-emd09" name="data-id-emd09" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_emd10">
                            <label for="data_emd10">EMD 10</label>
                            <input type="text" class="form-control" id="data_emd10" name="data_emd10">
                            <input type="text" class="form-control" id="data-id-emd10" name="data-id-emd10" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_emd11">
                            <label for="data_emd11">EMD 11</label>
                            <input type="text" class="form-control" id="data_emd11" name="data_emd11">
                            <input type="text" class="form-control" id="data-id-emd11" name="data-id-emd11" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_emd12">
                            <label for="data_emd12">EMD 12</label>
                            <input type="text" class="form-control" id="data_emd12" name="data_emd12">
                            <input type="text" class="form-control" id="data-id-emd12" name="data-id-emd12" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_emd13">
                            <label for="data_emd13">EMD 13</label>
                            <input type="text" class="form-control" id="data_emd13" name="data_emd13">
                            <input type="text" class="form-control" id="data-id-emd13" name="data-id-emd13" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_emd14">
                            <label for="data_emd14">EMD 14</label>
                            <input type="text" class="form-control" id="data_emd14" name="data_emd14">
                            <input type="text" class="form-control" id="data-id-emd14" name="data-id-emd14" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_man1">
                            <label for="data-man1">MAN 1</label>
                            <input type="text" class="form-control" id="data-man1" name="data_man1">
                            <input type="text" class="form-control" id="data-id-man1" name="data-id-man1" hidden>
                        </div>
                        <div class="form-group col-md-4 registro" data-campo="data_man2">
                            <label for="data-man2">MAN 2</label>
                            <input type="text" class="form-control" id="data-man2" name="data_man2">
                            <input type="text" class="form-control" id="data-id-man2" name="data-id-man2" hidden>
                        </div>
                        <!-- <div class="form-group col-md-4">
                            <label for="cm_valor">Valor Carga Maxima</label>
                            <input type="text" class="form-control" id="cm_valor" name="cm_valor">
                        </div> -->
                        <input type="hidden" class="form-control" id="cm_id" name="cm_id">
                    </div>



                    {{-- @if(empty($table_gen_cm))
                    <tr>
                        <input class="form-control" id="num_maquina" name="num_maquina" value="0">
                    </tr>
                    @else
                    <input class="form-control" id="num_maquina" name="num_maquina" value="{{ $registro->gen_cm_id }}">
                    @endif--}}

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

<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    new DataTable('#table_control_carga', {
        order: [
            [0, 'desc']
        ]
    });

    $(document).ready(function() {
        $('#DataEdit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var modal = $(this);

            // Llenar los campos del modal con los datos de la fila
            modal.find('#data_mb1').val(button.data('mb1'));
            modal.find('#data_mb2').val(button.data('mb2'));
            modal.find('#data_emd09').val(button.data('emd09'));
            modal.find('#data_emd10').val(button.data('emd10'));
            modal.find('#data_emd11').val(button.data('emd11'));
            modal.find('#data_emd12').val(button.data('emd12'));
            modal.find('#data_emd13').val(button.data('emd13'));
            modal.find('#data_emd14').val(button.data('emd14'));
            modal.find('#data-man1').val(button.data('man1'));
            modal.find('#data-man2').val(button.data('man2'));
            modal.find('#data-id-mb1').val(button.data('id-mb1'));
            modal.find('#data-id-mb2').val(button.data('id-mb2'));
            modal.find('#data-id-emd09').val(button.data('id-emd09'));
            modal.find('#data-id-emd10').val(button.data('id-emd10'));
            modal.find('#data-id-emd11').val(button.data('id-emd11'));
            modal.find('#data-id-emd12').val(button.data('id-emd12'));
            modal.find('#data-id-emd13').val(button.data('id-emd13'));
            modal.find('#data-id-emd14').val(button.data('id-emd14'));
            modal.find('#data-id-man1').val(button.data('id-man1'));
            modal.find('#data-id-man2').val(button.data('id-man2'));


            // modal.find('#cm_valor').val(button.data('valor-cm'));
            // modal.find('#cm_ciudad').val(button.data('nombre-ciudad'));
            // modal.find('#cm_id').val(button.data('id'));
        });
    });

    //$(document).ready(function() {
    //
    //    $('#DataEdit').on('show.bs.modal', function(event) {
    //        var button = $(event.relatedTarget); // Botón que activó el modal
    //        var modal = $(this);
    //
    //        // Llenar los campos del modal con los datos de la fila
    //
    //
    //        modal.find('#data_mb1').val(button.data('data_mb1'));
    //        // modal.find('#cm_valor').val(button.data('valor-cm'));
    //        // modal.find('#cm_ciudad').val(button.data('nombre-ciudad'));
    //
    //        //  modal.find('#cm_id').val(button.data('id'));
    //    });
    //});
</script>
<script>
    $(document).ready(function() {
        //script para el registro de la carga maxima 

        $('#registro_cm').on('submit', function(event) {
            event.preventDefault();
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("savecm") }}',
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
                    /*   $('#dataTable tbody').append(
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
        //registro carga minima
        $('#registro_cmin').on('submit', function(event) {
            event.preventDefault();
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("savecmin") }}',
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
                    /*   $('#dataTable tbody').append(
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
    });
</script>
<script>
    $(document).ready(function() {


        $('#registro_cg').on('submit', function(event) {
            event.preventDefault();
            document.querySelectorAll('input, select').forEach(input => input.disabled = false);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("registro_cg") }}',
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


<script>
    $('#updateForm_control_carga').on('submit', function(event) {
        event.preventDefault();

        let registros = [];

        $('.registro').each(function() {
            let campo = $(this).data('campo');

            let maquina = campo.split('data_')[1]; // "man1"

            // let campo = 'gen_cg_valor_kw';
            let valor = $(this).find(`input[name="${campo}"]`).val();
            let id = $(this).find(`input[name="data-id-${campo.split('_')[1]}"]`).val();
            console.log({
                campo: campo,
                valor: valor,
                id: id,
                maquina: maquina

            });
            if (id) {
                registros.push({
                    id: id,
                    campo: campo,
                    valor: valor
                });
            }
        });


        let cm_id = $('#cm_id').val();
        let fecha = $('#cm_fecha').val();
        console.log({
            fecha: fecha
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            //url: 'actualizar-multiples-registros', 
            url: '{{ route("registro_multiple") }}',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                cm_id: cm_id,
                fecha: fecha,
                registros: registros
            }),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registros actualizados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.error || 'Ocurrió un error inesperado',
                });
            }
        });
    });
</script>


@stop