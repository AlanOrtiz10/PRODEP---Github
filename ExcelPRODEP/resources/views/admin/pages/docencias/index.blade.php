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
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-plus fa-xs icon-margin"></i> Importar registros
        </button>
    </div>
    <div class="text-right ml-auto">
        Exportar a
        <div class="d-inline-block">
            <a href="{{ route('export.docencia') }}">
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
                <tfoot>
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
                </tfoot>
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
            <form action="{{ route('import.excel.docencia') }}" method="post" enctype="multipart/form-data">
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
