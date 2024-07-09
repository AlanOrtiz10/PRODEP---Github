@extends('admin.layouts.base')
@section('title', 'Tutorias')
@section('content')
<h1 class="h3 mb-4 h3-custom">Datos de Tutorías</h1>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('import.tutorias') }}">Tutorías</a></li>
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
                    <img src="{{ asset('/assets/img/ExcelLogo.png') }}" alt="ExcelLogo" class="export-icon"> Excel
                </div>
            </a>
            <div class="export-option ml-1">
                <img src="{{ asset('/assets/img/PDFLogo.png') }}" alt="PDFLogo" class="export-icon"> PDF
            </div>
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
                        <th>Fecha</th>
                        <th>Tutor</th>
                        <th>Tipo</th>
                        <th>Grupo</th>
                        <th>Alumno(s)</th>
                        <th>Estatus</th>
                        <th>Motivo</th>
                        <th>Periodo</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Tutor</th>
                        <th>Tipo</th>
                        <th>Grupo</th>
                        <th>Alumno(s)</th>
                        <th>Estatus</th>
                        <th>Motivo</th>
                        <th>Periodo</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->fecha_registro }}</td>
                        <td>{{ $row->tutor }}</td>
                        <td>{{ $row->tipo_tutoria }}</td>
                        <td>{{ $row->grupo ? $row->grupo : 'No especificado' }}</td>
                        <td>{{ $row->alumno ? $row->alumno : 'Todo el grupo' }}</td>
                        <td>
                            @if ($row->estatus == 'ABIERTA')
                            <span class="texto-abierto">{{ ucwords(strtolower($row->estatus)) }}</span>
                            @elseif ($row->estatus == 'CERRADA')
                            <span class="texto-cerrado">{{ ucwords(strtolower($row->estatus)) }}</span>
                            @else
                            {{ $row->estatus }}
                            @endif
                        </td>
                        <td>{{ $row->motivo }}</td>
                        <td>{{ $row->periodo }}</td>
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

