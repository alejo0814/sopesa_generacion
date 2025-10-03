
<h1>SOPESA S.A E.S.P</h1>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">






<div id="result">
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Ultimo reporte diario-{{ $last_rep[0]->gen_repd_fecha ?? 'N/A'}}</h3>
        </div>
        <div class="card-body">


      

            <table id="miTabla" class="table table-bordered display compact">
                <thead>
                    <tr>
                        <th>PARAMETROS</th>
                        <th>UND</th>
                        <th>{{ $last_rep[0]->gen_ma_nombre ?? 'N/A'}}</th>
                        <th>{{ $last_rep[1]->gen_ma_nombre ?? 'N/A'}}</th>
                        <th>PLANTA 3D MAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CARGA MAXIMA DE CENTRAL</td>
                        <td>kW</td>

                    </tr>
                    <tr>
                        <td>GENERACION BRUTA *</td>
                        <td>kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_gen_bruta ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_gen_bruta ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_gen_bruta ?? 0) + ($last_rep[1]->gen_repd_gen_bruta ?? 0) }}</td>
                    <tr>
                        <td>CONSUMO PROPIO</td>
                        <td>kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_cons_propio ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cons_propio ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cons_propio ?? 0) + ($last_rep[1]->gen_repd_cons_propio ?? 0) }}</td>

                    </tr>
                    <tr>
                        <td>GENERACION NETA</td>
                        <td>kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_gen_neta ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_gen_neta ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_gen_neta ?? 0 )+ ($last_rep[1]->gen_repd_gen_neta ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CAPACIDAD NOMINAL</td>
                        <td>kW</td>
                        <td>{{ $last_rep[0]->gen_repd_cap_nominal ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cap_nominal ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cap_nominal ?? 0) + ($last_rep[1]->gen_repd_cap_nominal ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CAPACIDAD EFFECTIVA</td>
                        <td>kW</td>
                        <td>{{ $last_rep[0]->gen_repd_cap_efectiva ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cap_efectiva ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cap_efectiva ?? 0) +($last_rep[1]->gen_repd_cap_efectiva ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CARGA PROMEDIO</td>
                        <td>kW</td>
                        <td>{{ $last_rep[0]->gen_repd_carg_promedio ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_carg_promedio ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_carg_promedio ?? 0) + ($last_rep[1]->gen_repd_carg_promedio ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>INDICE CARGA PROMEDIO NOMINAL</td>
                        <td>%</td>
                        <td>{{ $last_rep[0]->gen_repd_indice_carg_promed_nominal ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_indice_carg_promed_nominal ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_indice_carg_promed_nominal ?? 0) + ($last_rep[1]->gen_repd_indice_carg_promed_nominal ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CANTIDAD HORAS DE OPERACIÓN *</td>
                        <td>Hrs</td>
                        <td>{{ $last_rep[0]->gen_repd_hrs_operacion ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_hrs_operacion ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_hrs_operacion ?? 0) + ($last_rep[1]->gen_repd_hrs_operacion ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>HORAS DISPONIBLES *</td>
                        <td>Hrs</td>
                        <td>{{ $last_rep[0]->gen_repd_hrs_disponibilidad ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_hrs_disponibilidad ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_hrs_disponibilidad ?? 0) + ($last_rep[1]->gen_repd_hrs_disponibilidad ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>DISPONIBILIDAD GENERADORES</td>
                        <td>%</td>
                        <td>{{ $last_rep[0]->gen_repd_disp_generador ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_disp_generador ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_disp_generador ?? 0) + ($last_rep[1]->gen_repd_disp_generador ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CONSUMO COMBUSTIBLE</td>
                        <td>Lts</td>
                        <td>{{ $last_rep[0]->gen_repd_cons_combustible_lts ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cons_combustible_lts ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cons_combustible_lts ?? 0) + ($last_rep[1]->gen_repd_cons_combustible_lts ?? 0)}}</td>
                        <!-- <td>{{ $data->consumo_combustible_lts_man_01 ?? 0 ?? 'N/A'}}</td> -->
                        <!-- <td>{{ $data->consumo_combustible_lts_man_02 ?? 0 ?? 'N/A'}}</td> -->
                        <!-- <td>{{ $data->consumo_combustible_lts_planta ?? 0 ?? 'N/A'}}</td> -->
                    </tr>
                    <tr>
                        <td>CONSUMO COMBUSTIBLE *</td>
                        <td>gal</td>
                        <td>{{ $last_rep[0]->gen_repd_cons_combustible_gal ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cons_combustible_gal ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cons_combustible_gal ?? 0) + ($last_rep[1]->gen_repd_cons_combustible_gal ?? 0)}}</td>
                        <!--   <td>{{ $data->consumo_combustible_gal_man_01 ?? 0 ?? 'N/A'}}</td> -->
                        <!--   <td>{{ $data->consumo_combustible_gal_man_02 ?? 0 ?? 'N/A'}}</td> -->
                        <!--   <td>{{ $data->consumo_combustible_gal_planta ?? 0 ?? 'N/A'}}</td> -->
                    </tr>
                    <tr>
                        <td>EFICIENCIA COMBUSTIBLE BRUTA</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_efi_comb_bruta ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_efi_comb_bruta ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_efi_comb_bruta ?? 0) + ($last_rep[1]->gen_repd_efi_comb_bruta ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>EFICIENCIA COMBUSTIBLE NETA</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_efi_comb_neta ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_efi_comb_neta ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_efi_comb_neta ?? 0) + ($last_rep[1]->gen_repd_efi_comb_neta ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CONSUMO COMB. ESPECIFICO BRUTO</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_con_comb_esp_bruto ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_con_comb_esp_bruto ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_con_comb_esp_bruto ?? 0) + ($last_rep[1]->gen_repd_con_comb_esp_bruto ?? 0)}}</td>
                    </tr>
                    <tr>
                        <td>CONSUMO COMB. ESPECIFICO NETO</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_cons_comb_esp_neto ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cons_comb_esp_neto ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cons_comb_esp_neto ?? 0) + ($last_rep[1]->gen_repd_cons_comb_esp_neto ?? 0)}}</td>
                    </tr>
                    <tr>
                        <td>EXISTENCIA ACEITE LUBR. TQ ALMACEN. *</td>
                        <td>gal/kWh</td>
                        <td>??</td>
                        <td>??</td>
                        <td>??</td>


                    </tr>
                    <tr>
                        <td>CONSUMO ACEITE *</td>
                        <td>gal</td>
                        <td>{{ $last_rep[0]->gen_repd_cons_aceite_gal ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cons_aceite_gal ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cons_aceite_gal ?? 0) + ($last_rep[1]->gen_repd_cons_aceite_gal ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>CONSUMO ACEITE</td>
                        <td>lts</td>
                        <td>{{ $last_rep[0]->gen_repd_cons_aceite_lts ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_cons_aceite_lts ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_cons_aceite_lts ?? 0) + ($last_rep[1]->gen_repd_cons_aceite_lts ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>HORAS TRABAJO MOTOR & T/C *</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_hrs_trab_motor_tc ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_hrs_trab_motor_tc ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_hrs_trab_motor_tc ?? 0) + ($last_rep[1]->gen_repd_hrs_trab_motor_tc ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>HORAS DEL ULTIMO OVERHAUL</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_hrs_last_overhaul ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_hrs_last_overhaul ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_hrs_last_overhaul ?? 0) + ($last_rep[1]->gen_repd_hrs_last_overhaul ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>HORAS DESPUES ULTIMO MANT-TO</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_hrs_last_mantenimiento ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_hrs_last_mantenimiento ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_hrs_last_mantenimiento ?? 0) + ($last_rep[1]->gen_repd_hrs_last_mantenimiento ?? 0)}}</td>

                    </tr>
                    <tr>
                        <td>HORAS TRABAJADAS ACEITE LUBR. MOTOR *</td>
                        <td>gal/kWh</td>
                        <td>{{ $last_rep[0]->gen_repd_hrs_trab_ace_lub_motor ?? 'N/A'}}</td>
                        <td>{{ $last_rep[1]->gen_repd_hrs_trab_ace_lub_motor ?? 'N/A'}}</td>
                        <td>{{ ($last_rep[0]->gen_repd_hrs_trab_ace_lub_motor ?? 0) + ($last_rep[1]->gen_repd_hrs_trab_ace_lub_motor ?? 0)}}</td>

                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>


<link rel="stylesheet" href="/css/admin_custom.css">


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $('#miTabla').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>

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

