<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario de Importación de Excel</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }
        .table-responsive {
            margin: 30px 0;
        }
        .table-wrapper {
            min-width: 1000px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }
        .table-title {
            padding-bottom: 15px;
            background: #299be4;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }
        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }
        .table-title .btn {
            color: #566787;
            float: right;
            font-size: 13px;
            background: #fff;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }
        .table-title .btn:hover, .table-title .btn:focus {
            color: #566787;
            background: #f2f2f2;
        }
        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }
        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }
        table.table tr th, table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }
        table.table tr th:first-child {
            width: 60px;
        }
        table.table tr th:last-child {
            width: 100px;
        }
        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }
        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }
        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }    
        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }
        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
        }
        table.table td a:hover {
            color: #2196F3;
        }
        table.table td a.settings {
            color: #2196F3;
        }
        table.table td a.delete {
            color: #F44336;
        }
        table.table td i {
            font-size: 19px;
        }
        .status {
            font-size: 30px;
            margin: 2px 2px 0 0;
            display: inline-block;
            vertical-align: middle;
            line-height: 10px;
        }
        .text-success {
            color: #10c469;
        }
        .text-info {
            color: #62c9e8;
        }
        .text-warning {
            color: #FFC107;
        }
        .text-danger {
            color: #ff5b5b;
        }
        .pagination {
            float: right;
            margin: 0 0 5px;
        }
        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }
        .pagination li a:hover {
            color: #666;
        }    
        .pagination li.active a, .pagination li.active a.page-link {
            background: #03A9F4;
        }
        .pagination li.active a:hover {        
            background: #0397d6;
        }
        .pagination li.disabled i {
            color: #ccc;
        }
        .pagination li i {
            font-size: 13px;
            padding-top: 6px;
        }
        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if ($errors->has('file'))
        <div class="alert alert-danger">
            {{ $errors->first('file') }}
        </div>
        @endif

        <form action="{{ route('import.excel') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Seleccione un archivo Excel:</label>
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>

        @if($data->isNotEmpty())
        <h2 class="mt-5">Registros en la Base de Datos:</h2>
        <!-- Botón para generar constancia de docencia -->
        <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#constanciaModal">
            Generar constancia de Docencia
        </button>
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2>Registros de <b>Docencia</b></h2>
                        </div>
                        <div class="col-sm-7">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#constanciaModal">
                                <i class="material-icons">&#xE147;</i> <span>Generar constancia</span>
                            </button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Profesor</th>
                            <th>Carrera</th>
                            <th>Grupo</th>
                            <th>Cuatrimestre</th>
                            <th>Asignatura</th>
                            <th>Alumnos</th>
                            <th>Asesorias Mes</th>
                            <th>Horas Semanales</th>
                            <th>Horas Extras</th>

                            <th>Periodo</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->nombre_profesor }}</td>
                            <td>{{ $row->nombre_carrera }}</td>
                            <td>{{ $row->grupo }}</td>
                            <td>{{ $row->cuatrimestre ? $row->cuatrimestre . '$' : 'No especificado' }}</td>
                            <td>{{ $row->asignatura }}</td>
                            <td>{{ $row->numero_alumnos }}</td>
                            <td>{{ $row->asesorias_mes }}</td>
                            <td>{{ $row->horas_semanales_curso }}</td>
                            <td>{{ $row->horas_extras_mes ? $row->horas_extras_mes . '$' : 'N/A' }}</td>
                            <td>{{ $row->periodo_escolar }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="clearfix">
                    <div class="hint-text">Mostrando <b>{{ $data->count() }}</b> resultados de <b>{{ $data->total()}}</b></div>
                    <ul class="pagination">
                        @if ($data->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                        @else
                            <li class="page-item"><a href="{{ $data->previousPageUrl() }}" class="page-link">Anterior</a></li>
                        @endif
                
                        @php
                            $start = max(1, $data->currentPage() - 4);
                            $end = min($start + 9, $data->lastPage());
                        @endphp
                
                        @if ($start > 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                
                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $data->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                            @else
                                <li class="page-item"><a href="{{ $data->url($i) }}" class="page-link">{{ $i }}</a></li>
                            @endif
                        @endfor
                
                        @if ($end < $data->lastPage())
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                
                        @if ($data->hasMorePages())
                            <li class="page-item"><a href="{{ $data->nextPageUrl() }}" class="page-link">Siguiente</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
                        @endif
                    </ul>
                </div>
                
                
                
            </div>
        </div>
        @endif

        <!-- Modal para generar constancia de docencia -->
        <div class="modal fade" id="constanciaModal" tabindex="-1" role="dialog" aria-labelledby="constanciaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="constanciaModalLabel">Generar constancia de Docencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="nombreProfesor">Nombre del profesor:</label>
                                <select class="form-control select2" id="nombreProfesor" data-placeholder="Selecciona un maestro">
                                    <option value="" hidden>Selecciona un maestro</option> <!-- Opción por defecto oculta -->
                                    @foreach($profesores as $profesor => $carreras)
                                        <option value="{{ $profesor }}" data-carreras="{{ json_encode($carreras) }}">{{ $profesor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="carrera">Carreras disponibles:</label>
                                <select class="form-control" id="carrera">
                                    <!-- Opciones del select -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cuatrimestre">Cuatrimestre:</label>
                                <select class="form-control" id="cuatrimestre">
                                    <!-- Opciones del select -->
                                    @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="horasExtras">Horas extras de enseñanza al mes:</label>
                                <select class="form-control" id="horasExtras">
                                    <!-- Opciones del select -->
                                    @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary">Generar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar enlaces a Bootstrap JS y sus dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Agregar Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Script personalizado -->
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#nombreProfesor').on('change', function() {
                var carreras = $(this).find('option:selected').data('carreras');
                $('#carrera').empty();
                $.each(carreras, function(index, carrera) {
                    $('#carrera').append($('<option>', {
                        value: carrera,
                        text: carrera
                    }));
                });
                $('#carrera').trigger('change');
            });
        });
    </script>
</body>
</html>
