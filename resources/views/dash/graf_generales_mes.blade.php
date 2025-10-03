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

<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var labels1 = @json($labels1);
        var values1 = @json($values1);
        var hrs_acum_1 = @json($hrs_acum_1);

        // Imprimir los datos en la consola
        //console.log('Labels1:', labels1);
        //console.log('Values:', values1);
        //console.log('hrs_acum_1:', hrs_acum_1);

        var ctx = document.getElementById('miGrafica').getContext('2d');
        var miGrafica = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: @json($labels1), // Etiquetas
                datasets: [{
                    label: 'MAN 1',
                    data: @json($values1), // Datos
                    backgroundColor: 'rgba(193, 212, 19, 0.74)',
                    borderColor: 'rgba(112, 192, 75, 0.96)',
                    borderWidth: 1
                }, {
                    label: 'MAN 2',
                    data: @json($values2), // Datos
                    backgroundColor: 'rgba(29, 111, 219, 0.78)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {

                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Horas disponibilidad',
                            rotation: 180,
                        },
                        max: 24,

                    }
                },
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        var comb_acum1 = @json($comb_acum1);
        var fech_comb1 = @json($fech_comb1);
        var fech_comb2 = @json($fech_comb2);
        var comb_acum2 = @json($comb_acum2);



        // Imprimir los datos en la consola
        console.log('Labels Lineal:', fech_comb2);
        console.log('Values Lineal:', comb_acum2);
        console.log('Labels Lineal:', fech_comb1);
        console.log('Values Lineal:', comb_acum1);

        var ctxLineal = document.getElementById('miGraficaLineal').getContext('2d');
        var miGraficaLineal = new Chart(ctxLineal, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: @json($fech_comb1), // Etiquetas
                datasets: [{
                        label: 'MAN 1',
                        data: @json($comb_acum1), // Datos
                        backgroundColor: 'rgba(193, 212, 19, 0.74)',
                        borderColor: 'rgba(112, 192, 75, 0.96)',
                        //borderWidth: 1,
                        //fill: false
                    },
                    {
                        label: 'MAN 2',
                        data: @json($comb_acum2), // Datos
                        backgroundColor: 'rgba(29, 111, 219, 0.78)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        // borderWidth: 1,
                        // fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Galones',
                            rotation: 180,
                        },
                        //max: 24,

                    }
                },

                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        //text: 'Chart.js Line Chart'
                    }
                }
            },
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var comb_lts_1 = @json($comb_lts_1);
        var fech_comb1 = @json($fecha_lts_1);

        var comb_lts_2 = @json($comb_lts_2);



        // Imprimir los datos en la consola
        console.log('Labels Lineal:', comb_lts_2);
        console.log('Values Lineal:', comb_lts_2);
        console.log('Labels Lineal:', comb_lts_2);
        console.log('Values Lineal:', comb_lts_2);

        var ctxLineal = document.getElementById('combustible_diario_litros').getContext('2d');
        var combustible_diario_litros = new Chart(ctxLineal, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: @json($fech_comb1), // Etiquetas
                datasets: [{
                        label: 'MAN 1',
                        data: @json($comb_lts_1), // Datos
                        backgroundColor: 'rgba(193, 212, 19, 0.74)',
                        borderColor: 'rgba(112, 192, 75, 0.96)',
                        //borderWidth: 1,
                        //fill: false
                    },
                    {
                        label: 'MAN 2',
                        data: @json($comb_lts_2), // Datos
                        backgroundColor: 'rgba(29, 111, 219, 0.78)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        // borderWidth: 1,
                        // fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Litros',
                            rotation: 180,
                        },
                        //max: 24,

                    }
                },

                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        //text: 'Chart.js Line Chart'
                    }
                }
            },
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        //,'aceite_acum1','fech_aceite1','aceite_acum2','fech_aceite2',
        var aceite_acum1 = @json($aceite_acum1);
        var fech_aceite1 = @json($fech_aceite1);

        var aceite_acum2 = @json($aceite_acum2);



        // Imprimir los datos en la consola
        console.log('Labels Lineal:', aceite_acum1);
        console.log('Values Lineal:', fech_aceite1);
        console.log('Labels Lineal:', aceite_acum2);
        //  console.log('Values Lineal:', comb_lts_2);

        var ctxLineal = document.getElementById('aceite_diario').getContext('2d');
        var combustible_diario_litros = new Chart(ctxLineal, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: @json($fech_aceite1), // Etiquetas
                datasets: [{
                        label: 'MAN 1',
                        data: @json($aceite_acum1), // Datos
                        backgroundColor: 'rgba(193, 212, 19, 0.74)',
                        borderColor: 'rgba(112, 192, 75, 0.96)',
                        //borderWidth: 1,
                        //fill: false
                    },
                    {
                        label: 'MAN 2',
                        data: @json($aceite_acum2), // Datos
                        backgroundColor: 'rgba(29, 111, 219, 0.78)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        // borderWidth: 1,
                        // fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Litros',
                            rotation: 180,
                        },
                        //max: 24,

                    }
                },

                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        //text: 'Chart.js Line Chart'
                    }
                }
            },
        });
    });
</script>


@stop