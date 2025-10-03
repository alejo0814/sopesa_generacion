@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <h1>Consultas generales</h1>
@stop

@section('content')


    <!-- <div class="row">
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
                                            {{-- <input type="date" class="form-control" id="fecha" name="fecha" placeholder=""> --}}
                                            <input type="month" name="fecha_m" id="fecha_m" class="form-control" value="">
                                        </div>
                                        {{-- <div class="col-6">
                            <label for="plant">Planta</label>
                            <input type="text" id="plant" name="plant" class="form-control" placeholder="Ingrese el nombre de la planta">
                        </div> --}}
                                        <div class="col-6">
                                            <label>Planta Generadora</label>
                                            <select class="form-control" id="planta_id" name="planta_id">
                                                <option value="">Seleccione una planta</option>
                                                @foreach ($plantas as $planta)
    <option value="{{ $planta->gen_pl_id }}">{{ $planta->gen_pl_nombre }}</option>
    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Consultar</button>
                                </form>




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









                            </div>
                        </div>
                    </div> -->

    @php
        function formatNumber($number)
        {
            return isset($number) ? number_format($number, 0, '.', ',') : 'N/A';
        }

    @endphp
    <div class="col-lg-4">
        {{-- <div class="card card-secondary">
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
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div> --}}


    </div>
    <div class="col-sm-10">
        <div class="card card-secondary">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Control Carga dia {{ $fechaHoy ?? 'N/A' }}</h3>

                <form class="d-flex ml-auto" id="buscar_control_carga_dia" action="{{ route('buscar_c_c_graf_dia_ajax') }}"
                    method="POST">
                    @csrf
                    <input id="fech_buscar_c_c_d" name="fech_buscar_c_c_d" class="form-control me-2" type="date" />
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>

                {{-- <form class="d-flex ml-auto" role="search" id="buscar_control_carga_dia" action="{{ route('buscar_c_c_graf_dia') }}" method="POST">
            @csrf
            <input id="fech_buscar_c_c_d" name="fech_buscar_c_c_d" class="form-control me-2" type="date" placeholder="" aria-label="" />
            <button class="btn btn-outline-success" type="submit">Search</button>
            </form> --}}
            </div>
            <div class="card-body">
                <div>
                    <canvas id="grafica_control_carga_hoy" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
            <div class="card-footer">
                {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
            </div>
            <div id="result"></div>
        </div>
    </div>
    <div class="col-lg-10">
        <div class="card card-secondary card-tabs">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Consulta control de carga por mes {{-- $fechaHoy ?? 'N/A' --}}</h3>
                <form class="d-flex ml-auto" role="search" id="buscar_control_carga_mes"
                    action="{{ route('buscar_c_c_graf_mes_ajax') }}" method="POST">
                    @csrf
                    <input id="fecha_buscar_c_c_m" name="fecha_buscar_c_c_m" class="form-control me-2" type="month"
                        placeholder="" aria-label="" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>

            <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-two-home" role="tabpanel"
                        aria-labelledby="custom-tabs-two-home-tab">
                        <canvas id="grafica_max_min_mes" style="width: 100%; height: 300px;"></canvas>
                    </div>
                    {{-- <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                        <canvas id="combustible_diario_litros"></canvas>
                    </div> --}}
                </div>


            </div>
        </div>

    </div>

    <!--
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
                                {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-aceite2" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Litros</a>
                    </li>-- }}


            </ul>
            {{-- <h3 class="card-title"></h3> --}}
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-two-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-two-aceite1" role="aceite1" aria-labelledby="custom-tabs-two-home-tab">
                                    <canvas id="aceite_diario"></canvas>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-two-aceite1" role="aceite2" aria-labelledby="custom-tabs-two-profile-tab">
                                    {{-- <canvas id="combustible_diario_litros"></canvas> --}}
                                </div>
                            </div>


                        </div>
                    </div>

                </div> -->

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
        let grafica_control_carga_hoy;

        document.addEventListener('DOMContentLoaded', function() {
            const ctxLineal = document.getElementById('grafica_control_carga_hoy').getContext('2d');


            grafica_control_carga_hoy = new Chart(ctxLineal, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'San andres',
                        data: [],
                        backgroundColor: 'rgba(193, 212, 19, 0.74)',
                        borderColor: 'rgba(112, 192, 75, 0.96)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Galones',
                                rotation: 180,
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Hora'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            const form = document.getElementById('buscar_control_carga_dia');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const fecha = document.getElementById('fech_buscar_c_c_d').value;
                const token = document.querySelector('input[name="_token"]').value;

                fetch("{{ route('buscar_c_c_graf_dia_ajax') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify({
                            fech_buscar_c_c_d: fecha
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Datos recibidos:", data);

                        // ✅ Actualiza el título con la fecha recibida
                        document.querySelector('.card-title').textContent = 'Control Carga día ' + (data
                            .fechaHoy ?? 'N/A');



                        grafica_control_carga_hoy.data.labels = data.labels;
                        grafica_control_carga_hoy.data.datasets[0].data = data.data;
                        grafica_control_carga_hoy.update();
                    })
                    .catch(error => {
                        console.error("Error al actualizar la gráfica:", error);
                    });
            });
        });
    </script>





    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //
        //     //'carga_max_hora','carga_max_fech','carga_min_hora','carga_min_fech'
        //     var carga_max_val = @json($carga_max_val);
        //     var carga_max_fech = @json($carga_max_fech);
        //     var carga_min_val = @json($carga_min_val);
        //     var carga_min_fech = @json($carga_min_fech);
        //
        //
        //
        //     var carga_min_hora = @json($carga_min_hora); // Por ejemplo: ["dato1", "dato2", ...]
        //     var carga_max_hora = @json($carga_max_hora); // Por ejemplo: ["datoA", "datoB", ...]
        //
        //
        //
        //
        //     // console.log('Labels Lineal:', carga_diaria_hora);
        //     // console.log('Values Lineal:', carga_dia_data);
        //
        //
        //     var ctxLineal = document.getElementById('grafica_max_min_mes').getContext('2d');
        //     var grafica_control_carga_hoy = new Chart(ctxLineal, {
        //         type: 'line',
        //         data: {
        //             labels: carga_min_fech,
        //             datasets: [{
        //                     label: 'minima',
        //                     data: carga_min_val,
        //                     backgroundColor: 'rgb(51, 255, 0)',
        //                     borderColor: 'rgb(0, 255, 0)',
        //                     fill: false
        //                 },
        //                 {
        //                     label: 'maxima', // Nombre de la segunda línea
        //                     data: carga_max_val, // Datos de la segunda línea
        //                     backgroundColor: 'rgb(230, 9, 9)',
        //                     borderColor: 'rgb(255, 0, 0)',
        //
        //                     fill: false
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: true,
        //             maintainAspectRatio: false,
        //             scales: {
        //                 y: {
        //                     title: {
        //                         display: true,
        //                         text: 'Galones',
        //                         rotation: 180,
        //                     }
        //                 }
        //             },
        //             plugins: {
        //                 legend: {
        //                     position: 'top',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: 'Control de Carga Diaria'
        //                 },
        //                 tooltip: {
        //                     callbacks: {
        //                         afterBody: function(context) {
        //
        //
        //                             var index = context[0].dataIndex;
        //                             var datasetIndex = context[0].datasetIndex;
        //
        //                             if (datasetIndex === 0) {
        //                                 return 'Hora: ' + carga_min_hora[index];
        //                             } else if (datasetIndex === 1) {
        //                                 return 'Hora: ' + carga_max_hora[index];
        //                             }
        //
        //                             // var index = context[0].dataIndex;
        //                             // var extra1 = carga_min_hora[index];
        //                             // var extra2 = carga_max_hora[index];
        //                             // return [
        //                             //     'Dato extra 1: ' + extra1,
        //                             //     'Dato extra 2: ' + extra2
        //                             // ];
        //                         }
        //                     }
        //                 }
        //             }
        //         },
        //     });
        // });
    </script>

    {{-- <script>
        let grafica_max_min_mes; // Definida fuera del DOMContentLoaded

              let fechasMin = [];
        let min_hora = [];
         let max_hora = [];
        let fechasMax = [];

        document.addEventListener('DOMContentLoaded', function() {
            //const form = document.getElementById('buscar_control_carga_mes');
            const ctxLineal = document.getElementById('grafica_max_min_mes').getContext('2d');
        /*             let fechasMin = [];
        let min_hora = [];
         let max_hora = [];
        let fechasMax = [];
 */
            let grafica_max_min_mes = new Chart(ctxLineal, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'San andres',
                        data: [],
                        backgroundColor: 'rgba(193, 212, 19, 0.74)',
                        borderColor: 'rgba(112, 192, 75, 0.96)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: '--',
                                rotation: 180,
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Fecha'
                            }
                        }
                    },
                    plugins: {

                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    const index = tooltipItems[0].dataIndex;
                                    const datasetIndex = tooltipItems[0].datasetIndex;

                                    if (datasetIndex === 0) {
                                        // Carga mínima
                                        return `Fecha: ${fechasMin[index]}\nHora: ${min_hora[index]}`;
                                    } else {
                                        // Carga máxima
                                        return `Fecha: ${fechasMax[index]}\nHora: ${max_hora[index]}`;
                                    }
                                },
                                label: function(tooltipItem) {
                                    return `Valor: ${tooltipItem.formattedValue}`;
                                }
                            }
                        },

                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
            const form = document.getElementById('buscar_control_carga_mes');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const fecha = document.getElementById('fecha_buscar_c_c_m').value;
                const token = document.querySelector('input[name="_token"]').value;

                fetch("{{ route('buscar_c_c_graf_mes_ajax') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify({
                            fech_buscar_c_c_m: fecha
                        })
                    })
                    .then(response => response.json())

                    .then(data => {
                        console.log("Datos recibidos:", data);

                        const minDatos = data.min_val || [];
                        const maxDatos = data.max_val || [];
                        const min_hora = data.min_hora || [];
                        const max_hora = data.max_hora || [];


                        if (minDatos.length === 0 && maxDatos.length === 0) {
                            console.warn("No hay datos disponibles para mostrar en la gráfica.");
                            alert("No se encontraron datos para el mes seleccionado.");
                            return;
                        }

                        // Usamos las horas mínimas como etiquetas
                        grafica_max_min_mes.data.labels = data.max_fech;

                        grafica_max_min_mes.data.datasets = [];

                        if (minDatos.length > 0) {
                            grafica_max_min_mes.data.datasets.push({
                                label: 'Carga Mínima',
                                data: minDatos,
                                borderColor: 'rgba(112, 192, 75, 0.96)',
                                backgroundColor: 'rgba(193, 212, 19, 0.74)',
                                fill: false,
                                tension: 0.3
                            });
                        }

                        if (maxDatos.length > 0) {
                            grafica_max_min_mes.data.datasets.push({
                                label: 'Carga Máxima',
                                data: maxDatos.slice(0, minDatos
                                    .length), // Asegura que coincidan en longitud
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                fill: false,
                                tension: 0.3
                            });
                        }

                        grafica_max_min_mes.update();
                    })

            });
        });
    </script> --}}



    <script>
        let grafica_max_min_mes;
        let fechasMin = [];
        let min_hora = [];
        let fechasMax = [];
        let max_hora = [];

        document.addEventListener('DOMContentLoaded', function() {
            const ctxLineal = document.getElementById('grafica_max_min_mes').getContext('2d');

            grafica_max_min_mes = new Chart(ctxLineal, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: '--',
                                rotation: 180,
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Fecha'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    const index = tooltipItems[0].dataIndex;
                                    const datasetIndex = tooltipItems[0].datasetIndex;

                                    if (datasetIndex === 0) {
                                        return `Fecha: ${fechasMin[index]}\nHora: ${min_hora[index]}`;
                                    } else {
                                        return `Fecha: ${fechasMax[index]}\nHora: ${max_hora[index]}`;
                                    }
                                },
                                label: function(tooltipItem) {
                                    return `Valor: ${tooltipItem.formattedValue}`;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            const form = document.getElementById('buscar_control_carga_mes');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const fecha = document.getElementById('fecha_buscar_c_c_m').value;
                const token = document.querySelector('input[name="_token"]').value;

                fetch("{{ route('buscar_c_c_graf_mes_ajax') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify({
                            fech_buscar_c_c_m: fecha
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Datos recibidos:", data);

                        const minDatos = data.min_val || [];
                        const maxDatos = data.max_val || [];

                        fechasMin = data.min_fech || [];
                        min_hora = data.min_hora || [];
                        fechasMax = data.max_fech || [];
                        max_hora = data.max_hora || [];

                        if (minDatos.length === 0 && maxDatos.length === 0) {
                            console.warn("No hay datos disponibles para mostrar en la gráfica.");
                            alert("No se encontraron datos para el mes seleccionado.");
                            return;
                        }

                        grafica_max_min_mes.data.labels = fechasMax;

                        grafica_max_min_mes.data.datasets = [];

                        if (minDatos.length > 0) {
                            grafica_max_min_mes.data.datasets.push({
                                label: 'Carga Mínima',
                                data: minDatos,
                                borderColor: 'rgba(112, 192, 75, 0.96)',
                                backgroundColor: 'rgba(193, 212, 19, 0.74)',
                                fill: false,
                                tension: 0.3,

                                pointRadius: 6, // tamaño del punto normal
                                pointHoverRadius: 8 // tamaño del punto al pasar el mouse

                            });
                        }

                        if (maxDatos.length > 0) {
                            grafica_max_min_mes.data.datasets.push({
                                label: 'Carga Máxima',
                                data: maxDatos.slice(0, minDatos.length),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                fill: false,
                                tension: 0.3,

                                pointRadius: 6, // tamaño del punto normal
                                pointHoverRadius: 8 // tamaño del punto al pasar el mouse

                            });
                        }

                        grafica_max_min_mes.update();
                    });
            });
        });
    </script>




@stop
