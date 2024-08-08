<?php

namespace App\Http\Controllers;

use App\Exports\IndividualizadaExport;
use App\Imports\IndividualizadaImport;
use App\Models\Individualizada;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class IndividualizadaController extends Controller
{
    
    public function form()
    {
        $user = auth()->user();
        
        // Obtener el nombre completo formateado del docente autenticado
        $formattedName = $user->name . ' ' . $user->apellido_paterno . ' ' . $user->apellido_materno;
        
        // Inicializar las colecciones para periodos y carreras
        $periodos = collect();
        $carreras = collect();
        
        if ($user->level_id == 1) {
            // Para administradores: obtener todos los periodos y carreras
            $periodos = Individualizada::distinct()->pluck('periodo_escolar');
            $carreras = Individualizada::distinct()->pluck('carrera');
        } else {
            // Para docentes: obtener periodos y carreras relacionados con el docente autenticado
            $periodos = Individualizada::where('asesor_academico', $formattedName)->distinct()->pluck('periodo_escolar');
            $carreras = Individualizada::where('asesor_academico', $formattedName)->distinct()->pluck('carrera');
        }
        
        // Obtener los asesores únicos
        $asesores = Individualizada::distinct()->pluck('asesor_academico');
        
        // Inicializar la consulta de datos
        if ($user->level_id == 1) {
            // Para administradores: mostrar todos los registros
            $data = Individualizada::paginate(10);
        } elseif ($user->level_id == 2) {
            // Para docentes: filtrar registros por nombre del docente
            $data = Individualizada::whereRaw('BINARY asesor_academico = ?', [$formattedName])->paginate(10);
        }
        
        return view('admin.pages.individualizada.index', compact('data', 'periodos', 'carreras', 'asesores'));
    }
    

    

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Para importar registros, debe seleccionar un archivo.');
        }

        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|file',
        ], [
            'file.mimes' => 'El archivo debe tener una extensión .csv, .xls o .xlsx.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        try {
            // Importar directamente usando Laravel Excel sin conversión manual
            $import = new IndividualizadaImport();
            Excel::import($import, $filePath);

            $insertedRows = 0;
            foreach ($import->getData() as $data) {
                Individualizada::updateOrCreate(
                    ['matricula' => $data['matricula']], // Actualizar si ya existe por 'matricula'
                    $data
                );
                $insertedRows++;
            }

            return redirect()->back()->with('success', 'Se han importado ' . $insertedRows . ' registros correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
{
    $user = auth()->user();
    $query = Individualizada::query();

    // Aplicar filtros según el nivel del usuario
    if ($user->level_id == 2) {
        $formattedName = $user->name . ' ' . $user->apellido_paterno . ' ' . $user->apellido_materno;
        $query->whereRaw('BINARY asesor_academico = ?', [$formattedName]);
    }

    if ($request->filled('asesor_academico')) {
        $query->where('asesor_academico', $request->input('asesor_academico'));
    }

    if ($request->filled('periodo')) {
        $query->where('periodo_escolar', $request->input('periodo'));
    }

    if ($request->filled('carrera')) {
        $query->where('carrera', $request->input('carrera'));
    }

    // Seleccionar solo las columnas necesarias
    $columns = ['matricula', 'alumno_nombre', 'nombre_estadia', 'carrera', 'periodo_escolar'];
    if ($user->level_id == 1) {
        $columns[] = 'asesor_academico'; // Agregar columna solo para administradores
    }
    
    $data = $query->get($columns);

    return Excel::download(new IndividualizadaExport($data, $user->level_id), 'individualizada.xlsx');
}

    


    

}
