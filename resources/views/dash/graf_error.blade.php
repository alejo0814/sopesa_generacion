@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h1>Consultas generales</h1>
@stop

@section('content')


<div class="row">
    <div class="col-lg-">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Consulta</h3>
            </div>
            <div class="card-body">


           




                
                <form id="consulta_mes_grafica" action="{{ route('mes') }}" method="POST">
                @csrf
                    <div class="row form-group">
                        <div class="col-6">
                            <label>Fecha</label>
                            <input type="month" name="fecha_m" id="fecha_m" class="form-control" value="" >
                        </div>
                        <div class="col-6">
                            <label>Planta Generadora</label>
                            <select class="form-control" id="planta_id" name="planta_id">
                                <option value="">Seleccione una planta</option>
                                @foreach($plantas as $planta)
                                <option value="{{ $planta->gen_pl_id  }}">{{ $planta->gen_pl_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Consultar</button>
                </form>



{{--  
                @if ($errors->any())
                <div class="toast show align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">

                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach



                            @if (session('error'))
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-danger">
                                    <strong class="mr-auto">Error</strong>
                                    <small>Hace un momento</small>
                                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body">
                                    {{ session('error') }}
                                </div>
                            </div>
                            @endif
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
                @endif
--}}

@if ($errors->any())
     <div class="alert alert-danger">
         <ul>
             @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
         </ul>
     </div>
 @endif







            </div>
        </div>
    </div>



    {{-- <div class="col-lg-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Disponibilidad del servicio</h3>
            </div>
            <div class="card-body">
                <div>
                   
                </div>
            </div>
            <div class="card-footer">
               <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <div id="result"></div>
        </div>
    </div>--}}




    @php
    function formatNumber($number) {
    return isset($number) ? number_format($number, 0, '.', ',') : 'N/A';
    }

    @endphp
    <div class="col-lg-4">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Overhaul </h3>
            </div>
            <div class="card-body">
                <div class="progress-group">
                    <span class="progress-text">Man 1</span>
                    <span class="float-right"><b>{{formatNumber($hrs_acum1 ?? null)  }}</b>/18,000</span>
                    <x-adminlte-progress theme="blue" :value="formatNumber($hrs_acum_porce_1 ?? null)" animated with-label />
                </div>
                <div class="progress-group">
                    <span class="progress-text">Man 2</span>
                    <span class="float-right"><b>{{ formatNumber($hrs_acum2 ?? null)  }}</b>/18,000</span>
                    <x-adminlte-progress theme="yellow" :value="formatNumber($hrs_acum_porce_2 ?? null)" animated with-label />
                </div>
            </div>
        </div>
        {{-- <div class="card-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
        </div>--}}

    </div>
    <div class="col-lg-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Disponibilidad del servicio</h3>
            </div>
            <div class="card-body">
                <div>
                    <canvas id="miGrafica"></canvas>
                </div>
            </div>
            <div class="card-footer">
                {{--<button type="submit" class="btn btn-primary">Guardar</button>--}}
            </div>
            <div id="result"></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-secondary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="pt-2 px-3">
                        <h3 class="card-title">Consumo de combustible</h3>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Galones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Litros</a>
                    </li>


                </ul>
                {{--<h3 class="card-title"></h3>--}}
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                        <canvas id="miGraficaLineal"></canvas>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                        <canvas id="combustible_diario_litros"></canvas>
                    </div>
                </div>


            </div>
        </div>

    </div>


    <div class="col-lg-6">
        <div class="card card-secondary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="pt-2 px-3">
                        <h3 class="card-title">Consumo de combustible</h3>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-aceite1" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Galones</a>
                    </li>
                    {{--<li class="nav-item">
                        <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-aceite2" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Litros</a>
                    </li>-- }}


                </ul>
                {{--<h3 class="card-title"></h3>--}}
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-two-aceite1" role="aceite1" aria-labelledby="custom-tabs-two-home-tab">
                        <canvas id="aceite_diario"></canvas>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-two-aceite1" role="aceite2" aria-labelledby="custom-tabs-two-profile-tab">
                        {{--<canvas id="combustible_diario_litros"></canvas>--}}
                    </div>
                </div>


            </div>
        </div>

    </div>

</div>



@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
    .lineal {
        /*  display: flex;*/
        /* justify-content: space-around;*/
    }
</style>
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->

@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




@stop