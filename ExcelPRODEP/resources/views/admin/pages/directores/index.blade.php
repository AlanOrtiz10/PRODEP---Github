@extends('admin.layouts.base')
@section('title', 'Tutorias')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<h1 class="h3 mb-4 h3-custom">Directores</h1>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.dashboard.index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('directores') }}">Directores</a></li>
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
        <!-- Botón para agregar nuevo director -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addDirectorModal">
            <i class="fas fa-plus fa-xs icon-margin"></i> Agregar
        </button>
        
        <!-- Botón para eliminar director -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteDirectorModal">
            <i class="fas fa-trash-alt fa-xs icon-margin"></i> Eliminar
        </button>
    </div>
    <div class="text-right ml-auto">
        Exportar a
        <div class="d-inline-block">
            <a href="{{route('export.directores')}}">
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
                        <th>Director</th>
                        <th>Carrera</th>
                        <th>Nivel</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Director</th>
                        <th>Carrera</th>
                        <th>Nivel</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->director }}</td>
                        <td>{{ $row->carrera }}</td>
                        <td>{{ $row->nivel }}</td>
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

<!-- Modales -->

<!-- Modal Agregar Nuevo Director -->
<div class="modal fade" id="addDirectorModal" tabindex="-1" role="dialog" aria-labelledby="addDirectorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDirectorModalLabel">Agregar Nuevo Director</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('directores.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="director">Nombre del director</label>
                        <input type="text" class="form-control" id="director" name="director" required>
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera</label>
                        <input type="text" class="form-control" id="carrera" name="carrera" required>
                    </div>
                    <div class="form-group">
                        <label for="nivel">Nivel</label>
                        <select class="form-control" id="nivel" name="nivel" required>
                            <option value="TÉCNICO SUPERIOR UNIVERSITARIO">TÉCNICO SUPERIOR UNIVERSITARIO</option>
                            <option value="INGENIERÍA">INGENIERÍA</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Agregar Director</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Director -->
<div class="modal fade" id="editDirectorModal" tabindex="-1" role="dialog" aria-labelledby="editDirectorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDirectorModalLabel">Editar Director</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDirectorForm" method="POST">
                @csrf
                @method('PUT')
                <!-- Campo oculto para el ID -->
                <input type="hidden" id="edit_id" name="id">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_carrera">Carrera</label>
                        <input type="text" class="form-control" id="edit_carrera" name="carrera" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nivel">Nivel</label>
                        <select class="form-control" id="edit_nivel" name="nivel" required>
                            <option value="TÉCNICO SUPERIOR UNIVERSITARIO">TÉCNICO SUPERIOR UNIVERSITARIO</option>
                            <option value="INGENIERÍA">INGENIERÍA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_director">Director</label>
                        <input type="text" class="form-control" id="edit_director" name="director" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Eliminar Director -->
<div class="modal fade" id="deleteDirectorModal" tabindex="-1" role="dialog" aria-labelledby="deleteDirectorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDirectorModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteDirectorForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este director?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.edit-director-btn').click(function () {
            var id = $(this).data('id');
            var carrera = $(this).data('carrera');
            var nivel = $(this).data('nivel');
            var director = $(this).data('director');

            // Actualizar el formulario de edición con los datos del director seleccionado
            $('#editDirectorForm').attr('action', '{{ route('directores.update', ':id') }}'.replace(':id', id));
            $('#edit_id').val(id);  // Asignar el ID al campo oculto
            $('#edit_carrera').val(carrera);
            $('#edit_nivel').val(nivel);
            $('#edit_director').val(director);

            // Mostrar el modal de edición
            $('#editDirectorModal').modal('show');
        });

        $('.delete-director-btn').click(function () {
            var id = $(this).data('id');
            $('#deleteDirectorForm').attr('action', '{{ route('directores.destroy', ':id') }}'.replace(':id', id));
        });
    });

    // Limpiar el formulario de edición al cerrar el modal
    $('#editDirectorModal').on('hidden.bs.modal', function () {
        $('#editDirectorForm').trigger('reset');
    });
</script>
@endsection
