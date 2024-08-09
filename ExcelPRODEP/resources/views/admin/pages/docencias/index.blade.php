@extends('admin.layouts.base')
@section('title', 'Docencia')
@section('content')
<h1 class="h3 mb-4 h3-custom">Datos de Docencia</h1>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.dashboard.index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('import.docencia') }}">Docencia</a></li>
    </ol>
</nav>

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

<div class="d-flex justify-content-center align-items-center mb-3 mt-4">
    <div class="mr-auto">
        @if(auth()->user()->level_id == 1)
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-plus fa-xs icon-margin"></i> Importar registros
        </button>
        @endif
    </div>
    <div class="text-right ml-auto">
        Exportar a
        <div class="d-inline-block">
            <a href="#" data-toggle="modal" 
            data-target="{{ auth()->user()->level_id == 1 ? '#filterModal' : '#filterModalDocente' }}">
             <div class="export-option">
                 <img src="{{ asset('/assets/img/ExcelLogo.svg') }}" alt="ExcelLogo" class="export-icon"> Excel
             </div>
             </a>
            <a href="#" data-toggle="modal" data-target="#constanciaModal">
                <div class="export-option">
                    <img src="{{ asset('/assets/img/WordLogo.svg') }}" alt="WordLogo" class="export-icon"> Word
                </div>
            </a>
        </div>
    </div>
</div>

@if($data->isEmpty())
<div class="alert alert-warning">
    No se encontraron datos asociados a su cuenta de docencia.
</div>
@endif

@if($data->isNotEmpty())
<div class="card shadow mb-5 mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                        <th>Hrs. Semanales</th>
                        <th>Periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->nombre_profesor }}</td>
                        <td>{{ $row->nombre_carrera }}</td>
                        <td>{{ $row->grupo }}</td>
                        <td>{{ $row->cuatrimestre ?: 'No especificado' }}</td>
                        <td>{{ $row->asignatura }}</td>
                        <td>{{ $row->numero_alumnos }}</td>
                        <td>{{ $row->asesorias_mes }}</td>
                        <td>{{ $row->horas_semanales_curso }}</td>
                        <td>{{ $row->periodo_escolar }}</td>
                        <td>
                            <a href="{{ route('generate.doc', $row->id) }}">
                                <div class="export-option">
                                    <img src="{{ asset('/assets/img/WordLogo.svg') }}" alt="WordLogo" class="export-icon">
                                </div>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="hint-text">Mostrando <b>{{ $data->count() }}</b> resultados de <b>{{ $data->total() }}</b></div>
                <ul class="pagination">
                    @if ($data->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                    @else
                        <li class="page-item"><a href="{{ $data->previousPageUrl() }}" class="page-link">Anterior</a></li>
                    @endif

                    @for ($i = 1; $i <= $data->lastPage(); $i++)
                        <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                            <a href="{{ $data->url($i) }}" class="page-link">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($data->hasMorePages())
                        <li class="page-item"><a href="{{ $data->nextPageUrl() }}" class="page-link">Siguiente</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Modal de Filtro Nivel Administrador -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtrar y Exportar a Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('export.docencia') }}" method="get">
                <div class="modal-body">
                    @if (auth()->user()->level_id == 1)
                        <div class="form-group">
                            <label for="profesor">Profesor:</label>
                            <select class="form-control select" id="profesor" name="profesor">
                                <option value="">Todos los profesores</option>
                                @foreach ($profesores as $profesor)
                                    <option value="{{ $profesor }}">{{ $profesor }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="periodo">Periodo:</label>
                        <select class="form-control select" id="periodo" name="periodo">
                            <option value="">Exportar todos los periodos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="grupo">Grupo:</label>
                        <select class="form-control select" id="grupo" name="grupo">
                            <option value="">Exportar todos los grupos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control select" id="carrera" name="carrera">
                            <option value="">Exportar todas las carreras</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Exportar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Filtro de Nivel Docente -->
<div class="modal fade" id="filterModalDocente" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtrar y Exportar a Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('export.docencia') }}" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="periodo">Periodo:</label>
                        <select class="form-control select" id="periodo" name="periodo">
                            <option value="">Exportar todos los periodos</option>
                            @foreach ($periodos as $periodo)
                                <option value="{{ $periodo }}">{{ $periodo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="grupo">Grupo:</label>
                        <select class="form-control select" id="grupo" name="grupo">
                            <option value="">Exportar todos los grupos</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo }}">{{ $grupo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control select" id="carrera" name="carrera">
                            <option value="">Exportar todas las carreras</option>
                            @foreach ($carreras as $carrera)
                                <option value="{{ $carrera }}">{{ $carrera }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Exportar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal de Importación -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Importar Datos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('import.excel.docencia') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Archivo Excel:</label>
                        <input type="file" class="form-control-file" id="file" name="file" required>
                    </div>
                    @if(auth()->user()->level_id == 1)
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="update_existing" id="update_existing">
                            <label class="form-check-label" for="update_existing">
                                Actualizar datos existentes
                            </label>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Constancias -->
<div class="modal fade" id="constanciaModal" tabindex="-1" role="dialog" aria-labelledby="constanciaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="constanciaModalLabel">Generar Constancias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="periodo">Periodo:</label>
                        <select class="form-control select" id="periodo" name="periodo">
                            <option value="">Seleccionar periodo</option>
                            @foreach ($periodos as $periodo)
                                <option value="{{ $periodo }}">{{ $periodo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="grupo">Grupo:</label>
                        <select class="form-control select" id="grupo" name="grupo">
                            <option value="">Seleccionar grupo</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo }}">{{ $grupo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control select" id="carrera" name="carrera">
                            <option value="">Seleccionar carrera</option>
                            @foreach ($carreras as $carrera)
                                <option value="{{ $carrera }}">{{ $carrera }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Generar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inicializa Select2 en los elementos con la clase .select2
        $('.select2').select2();

        $('#profesor').change(function() {
            var profesor = $(this).val();

            if (profesor) {
                $.ajax({
                    url: "{{ route('filter.data') }}",
                    method: 'GET',
                    data: { profesor: profesor },
                    success: function(response) {
                        // Limpiar los campos actuales
                        $('#periodo').empty().append('<option value="">Exportar todos los periodos</option>');
                        $('#grupo').empty().append('<option value="">Exportar todos los grupos</option>');
                        $('#carrera').empty().append('<option value="">Exportar todas las carreras</option>');

                        // Añadir las opciones nuevas
                        response.periodos.forEach(function(periodo) {
                            $('#periodo').append('<option value="' + periodo + '">' + periodo + '</option>');
                        });

                        response.grupos.forEach(function(grupo) {
                            $('#grupo').append('<option value="' + grupo + '">' + grupo + '</option>');
                        });

                        response.carreras.forEach(function(carrera) {
                            $('#carrera').append('<option value="' + carrera + '">' + carrera + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>



@endsection

