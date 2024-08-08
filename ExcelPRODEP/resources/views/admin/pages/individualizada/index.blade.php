@extends('admin.layouts.base')
@section('title', 'Individualizada')
@section('content')
<h1 class="h3 mb-4 h3-custom">Datos de Individualizada</h1>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.dashboard.index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('index.individualizada') }}">Individualizada</a></li>
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
            <a href="#" data-toggle="modal" data-target="#exportModal">
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
                        @if(auth()->user()->level_id == 1)
                        <th>Asesor Academico</th>
                        @endif
                        <th>Expendiente</th>
                        <th>Alumno</th>
                        <th>Proyecto</th>
                        <th>Carrera</th>
                        <th>Periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        @if(auth()->user()->level_id == 1)
                        <td>{{ $row->asesor_academico }}</td>
                        @endif
                        <td>{{ $row->matricula ?: 'No especificado' }}</td>
                        <td>{{ $row->alumno_nombre }}</td>
                        <td>{{ $row->nombre_estadia }}</td>
                        <td>{{ $row->carrera }}</td>
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

<!-- Modal para exportar registros -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Exportar Registros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('export.individualizada') }}" method="GET">
                    @csrf

                    <!-- Filtro por Asesor Académico (Solo Nivel 1) -->
                    @if(auth()->user()->level_id == 1)
                    <div class="form-group">
                        <label for="asesor_academico">Asesor Académico:</label>
                        <select class="form-control select2" id="asesor_academico" name="asesor_academico">
                            <option value="">Todos</option>
                            @foreach($asesores as $asesor)
                                <option value="{{ $asesor }}">{{ $asesor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="asesor_academico">Periodo:</label>
                        <select class="form-control" name="periodo" id="periodo">
                            <option value="">Seleccionar</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo }}">{{ $periodo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="asesor_academico">Carrera:</label>
                        <select class="form-control" name="carrera" id="carrera">
                            <option value="">Seleccionar</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera }}">{{ $carrera }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Exportar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Importar Registros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('import.excel.individualizada') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Seleccione un archivo Excel:</label>
                        <input type="file" class="form-control-file" id="file" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para actualizar registros -->
@if(session('update_option'))
<div class="modal fade show" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" style="display: block; background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="updateForm" action="{{ route('update.imported.data') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Actualizar registros</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>El periodo escolar {{ session('periodo_escolar') }} ya existe en la base de datos. ¿Desea actualizar los registros?</p>
                    <input type="hidden" name="file_path" value="{{ session('file_path') }}">
                    <input type="hidden" name="periodo_escolar" value="{{ session('periodo_escolar') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
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

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script>
    $(document).ready(function() {
        // Mostrar el modal si la sesión 'update_option' está activa
        @if(session('update_option'))
            $('#updateModal').modal('show');
        @endif

        // Cerrar el modal al hacer clic en el botón 'Cancelar' dentro del modal
        $('#updateModal button[data-dismiss="modal"]').on('click', function() {
            $('#updateModal').modal('hide');
        });

        // Cerrar el modal al hacer clic en el botón de cerrar ('X')
        $('#updateModal .close').on('click', function() {
            $('#updateModal').modal('hide');
        });
    });
</script>
@endpush
