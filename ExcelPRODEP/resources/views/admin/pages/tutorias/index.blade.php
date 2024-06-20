@extends('admin.layouts.base')
@section('title', 'Tutorias')
@section('content')
<h1 class="h3 mb-4  h3-custom">Datos de Tutorías</h1>

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
                            <td>{{ $row->estatus }}</td>
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
@endsection
