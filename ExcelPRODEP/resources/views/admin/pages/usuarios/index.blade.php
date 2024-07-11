@extends('admin.layouts.base')
@section('title', 'Usuarios')
@section('content')
<h1 class="h3 mb-4 h3-custom">Usuarios</h1>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.dashboard.index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('index.usuarios') }}">Usuarios</a></li>
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
            <a href="{{route('export.tutorias')}}">
                <div class="export-option">
                    <img src="{{ asset('/assets/img/ExcelLogo.svg') }}" alt="ExcelLogo" class="export-icon"> Excel
                </div>
            </a>
        </div>
        
    </div>
</div>

@if($data->isNotEmpty())
<div class="card shadow mb-5 mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>CURP</th>
                        <th>Genero</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Estado</th>
                        <th>Nivel</th>
                        <th style="width: 150px;">Acciones</th> <!-- Ajuste de ancho para la columna de Acciones -->
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>CURP</th>
                        <th>Genero</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Estado</th>
                        <th>Nivel</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->apellido_paterno }}  {{ $row->apellido_materno }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->curp }}</td>
                        <td>{{ $row->genero == 'masculino' ? 'M' : ($row->genero == 'femenino' ? 'F' : '') }}</td>
                        <td>{{ $row->fecha_nacimiento }}</td>
                        <td>
                            @if ($row->status == 1)
                            <span class="texto-abierto">Activo</span>
                            @elseif ($row->status == 0)
                            <span class="texto-cerrado">Inactivo</span>
                            @else
                            {{ $row->status }}
                            @endif
                        </td>
                        <td>{{ $row->level ? $row->level->name : 'N/A' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-warning edit-director-btn"
                               title="Editar"
                               data-toggle="modal"
                               data-target="#editDirectorModal"
                               data-id="{{ $row->id }}"
                               data-carrera="{{ $row->carrera }}"
                               data-nivel="{{ $row->nivel }}"
                               data-director="{{ $row->director }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-primary ml-2 delete-director-btn"
                               title="Eliminar"
                               data-toggle="modal"
                               data-target="#deleteDirectorModal"
                               data-id="{{ $row->id }}">
                                <i class="fas fa-trash-alt"></i>
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
            <form action="{{ route('import.excel.tutorias') }}" method="post" enctype="multipart/form-data">
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

@endsection

<!-- Script personalizado -->
@section('scripts')
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
@endsection
