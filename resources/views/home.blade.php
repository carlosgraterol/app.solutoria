@extends('layouts.app2')

@section('content')  
    <div class="table-responsive">
        <div class="mt-2" style="text-align: right">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Agregar registro
            </button>
        </div>

        <table class="table table-striped table-sm mt-2">
            <thead class="bort-none borpt-0">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Acciones</th>
                    <th scope="col">Código</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Unidad</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Indicador</th>
                    <th scope="col">Origen</th>
                </tr>
            </thead>
            <tbody id="itemsIndicadores">
                @if(count($indicadores) > 0)
                    @foreach ($indicadores as $indicador)
                        <tr id="item{{ $indicador->id }}">
                            <td>{{ $indicador->id }}</td>
                            <td>
                                <i class="bi bi-pencil-square align-text-bottom text-primary" onclick="edit({{ $indicador->id }})"></i>
                                <i class="bi bi-trash align-text-bottom text-danger" onclick="borrar({{ $indicador->id }})"></i>
                            </td>
                            <td>{{ $indicador->codigoIndicador }}</td>
                            <td>{{ $indicador->valorIndicador }}</td>
                            <td>{{ $indicador->unidadMedidaIndicador }}</td>
                            <td>{{ date('d-m-Y', strtotime($indicador->fechaIndicador)) }}</td>
                            <td>{{ $indicador->nombreIndicador }}</td>
                            <td>{{ $indicador->origenIndicador }}</td>
                        </tr>
                    @endforeach
                @else 
                    <tr>
                        <td colspan="7" class="text-center">Aún no hay historial de registros</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal modal-lg fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Ingrese el Indicador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label for="nombre">Nombre</label>
                                <input type="hidden" class="form-control" id="id" name="id" value="">
                                <input type="text" class="form-control" id="nombre" name="nombre" value="" required>
                            </div>
    
                            <div class="col-md-4">
                                <label for="codigo">Código</label>
                                <select class="form-control" id="codigo" required>
                                    <option value="">-- seleccione --</option>
                                    <option value="UF">UF</option>
                                    <option value="DOLAR">DÓLAR</option>
                                    <option value="EURO">EURO</option>
                                    <option value="IVP">IVP</option>
                                    <option value="TPM">TPM</option>
                                    <option value="BITCOIN">BITCOIN</option>
                                    <option value="LIBRA_COBRE">LIBRA COBRE</option>
                                </select>
                            </div>
    
                            <div class="col-md-4">
                                <label for="unidad">Unidad Medida</label>
                                <input type="text" class="form-control" id="unidad" name="unidad" value="" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label for="valor">Valor</label>
                                <input type="number" min="0" step="0.1" class="form-control" id="valor" name="valor" value="" required>
                            </div>
    
                            <div class="col-md-4">
                                <label for="fecha">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="" required>
                            </div>
    
                            <div class="col-md-4">
                                <label for="origen">Origen</label>
                                <input type="text" class="form-control" id="origen" name="origen" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="addRegistro">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')  
    <script>
        //Gráfico
            /* Gráfico Hitorico UF - Últimos 7 días */
            (() => {
            'use strict'
            feather.replace({ 'aria-hidden': 'true' })
            // Graphs
            const ctx = document.getElementById('graficoUF')
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        @if(count($grafico) > 0)
                            @foreach($grafico as $gra)
                                '{{ date('d-m-Y', strtotime($gra->fechaIndicador)) }}',
                            @endforeach
                        @endif
                    ],
                    datasets: [{
                        data: [
                            @if(count($grafico) > 0)
                                @foreach($grafico as $gra)
                                    {{ floatval($gra->valorIndicador) }},
                                @endforeach
                            @endif
                        ],
                        lineTension: 0,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        borderWidth: 4,
                        pointBackgroundColor: '#007bff'
                    }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: false
                                }
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                })
            })()
        //Fin Gráfico
    </script>

    <script>
        //Store Regitro
        $('#addRegistro').on('click', function() {
            let id     = $("#id").val();
            let nombre = $("#nombre").val();
            let codigo = $("#codigo").val();
            let unidad = $("#unidad").val();
            let valor  = $("#valor").val();
            let fecha  = $("#fecha").val();
            let origen = $("#origen").val();

            if(nombre == '' || codigo == '' || unidad == '' || valor == '' || fecha == '' || origen == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops! Éxisten campos vacíos',
                    text: 'Todos los campos son obligatorios'
                });
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url:  '{{ route('store') }}',
                    type: 'POST',
                    data: {
                        id:id,
                        nombreIndicador:nombre,
                        codigoIndicador:codigo,
                        unidadMedidaIndicador:unidad,
                        valorIndicador:valor,
                        fechaIndicador:fecha,
                        origenIndicador:origen,
                    },
                    success(response) {
                        console.log(response);
                        //Add nuevo item a la tabla
                        if(id == ''){
                            Swal.fire({
                                icon: 'success',
                                title: '¡Registro agregado éxitosamente!',
                                text: ''
                            });
                            let fechaFormato = fecha.split('-');
                            let fechaUpdate  = fechaFormato[2]+'-'+fechaFormato[1]+'-'+fechaFormato[0];
                            $('#itemsIndicadores').prepend(
                                `
                                <tr id="item${response.data.id}">
                                    <td>${response.data.id}</td>
                                    <td>
                                        <i class="bi bi-pencil-square align-text-bottom text-primary" onclick="edit(${response.data.id})"></i>
                                        <i class="bi bi-trash align-text-bottom text-danger" onclick="borrar(${response.data.id})"></i>
                                    </td>
                                    <td>${response.data.codigoIndicador}</td>
                                    <td>${response.data.valorIndicador}</td>
                                    <td>${response.data.unidadMedidaIndicador}</td>
                                    <td>${fechaUpdate}</td>
                                    <td>${response.data.nombreIndicador}</td>
                                    <td>${response.data.origenIndicador}</td>
                                </tr>
                                `
                            );
                        }else{
                            Swal.fire({
                                icon: 'success',
                                title: '¡El registro fue actualizado correctamente!',
                                text: ''
                            });
                            let fechaFormato = fecha.split('-');
                            let fechaUpdate  = fechaFormato[2]+'-'+fechaFormato[1]+'-'+fechaFormato[0];
                            $(`#item${id}`).each(function() {
                                $(this).find('td').eq(2).html(codigo);
                                $(this).find('td').eq(3).html(valor);
                                $(this).find('td').eq(4).html(unidad);
                                $(this).find('td').eq(5).html(fechaUpdate);
                                $(this).find('td').eq(6).html(nombre);
                                $(this).find('td').eq(7).html(origen);
                            });
                        }

                        //Reinicio de campos
                        $('#staticBackdrop').modal('hide');
                        $('#id').val('');
                        $('#nombre').val('');
                        $('#codigo').val('');
                        $('#unidad').val('');
                        $('#valor').val('');
                        $('#fecha').val('');
                        $('#origen').val('');   
                        //Se actualiza el Gráfico
                        actualizarGrafico(response.grafico);
                    },
                    error() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ha ocurrido un error al guardar los datos',
                            text: 'Por favor intentalo nuevamente'
                        });
                        console.log(response)
                    }
                });
            }
        });
        //Fin Store Regitro

        //Editar Regitro
        function edit(id){
            $(`#item${id}`).each(function() {
                let id          = $(this).find('td').eq(0).html();
                let codigo      = $(this).find('td').eq(2).html();
                let valor       = $(this).find('td').eq(3).html();
                let unidad      = $(this).find('td').eq(4).html();
                let fechaOrigen = $(this).find('td').eq(5).html();
                let nombre      = $(this).find('td').eq(6).html();
                let origen      = $(this).find('td').eq(7).html();

                let fechaFormato = fechaOrigen.split('-');
                let fecha        = fechaFormato[2]+'-'+fechaFormato[1]+'-'+fechaFormato[0];

                $("#id").val(id);
                $("#nombre").val(nombre);
                $("#codigo").val(codigo);
                $("#unidad").val(unidad);
                $("#valor").val(valor);
                $("#fecha").val(fecha);
                $("#origen").val(origen);
            });
            $('#staticBackdrop').modal('show');
        }
        //Editar Regitro

        //Borrar Regitro
        function borrar(id){
            Swal.fire({
                title: '',
                text: '¿Seguro(a) que deseas borrar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url:  '{{ route('borrar') }}',
                        type: 'POST',
                        data: {id},
                        success(response) {
                            if(response.status == 200){
                                $('#item'+id).remove(); 
                                actualizarGrafico(response.grafico);
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Ha ocurrido un error al borrar el registro',
                                    text: 'Por favor intentalo nuevamente'
                                });
                            }        
                        },
                        error() {
                            console.log(response)
                        }
                    });
                }
            })
        }
        //Fin Borrar Regitro

        //Filtro
        $('#filtro').on('click', function() {
            let fechaInicio = $("#fechaInicio").val();
            let fechaFin    = $("#fechaFin").val();

            if(fechaInicio == '' || fechaFin == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops! Éxisten campos vacíos',
                    text: 'Las fechas de Inicio y Fin son obligatorias'
                });
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url:  '{{ route('filtro') }}',
                    type: 'POST',
                    data: {fechaInicio, fechaFin},
                    success(response) {
                        console.log(response);
                        //Se actualiza el Gráfico con la data del filtro aplicado
                        actualizarGrafico(response.grafico);
                    },
                    error() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ha ocurrido un error al filtrar los datos',
                            text: 'Por favor intentalo nuevamente'
                        });
                        console.log(response)
                    }
                });
            }
        });
        //Fin Filtro

        //Actualizar Gráfico
        function actualizarGrafico(grafico){
            //console.log(grafico);
            const labels = [];
            const datasets = [];
            $.each(grafico, (index, item) => {
                let fechaFormato = item.fechaIndicador.split('-');
                let fechaNew     = fechaFormato[2]+'-'+fechaFormato[1]+'-'+fechaFormato[0];
                labels.push(fechaNew);
                datasets.push(item.valorIndicador);
            });
            /* Actualiza Gráfico Hitorico UF - Últimos 7 días */
            (() => {
            'use strict'
            feather.replace({ 'aria-hidden': 'true' })
            // Graphs
            const ctx = document.getElementById('graficoUF')
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: datasets,
                        lineTension: 0,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        borderWidth: 4,
                        pointBackgroundColor: '#007bff'
                    }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: false
                                }
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                })
            })()
        }
        //Fin Actualizar Gráfico
    </script>
@endsection
