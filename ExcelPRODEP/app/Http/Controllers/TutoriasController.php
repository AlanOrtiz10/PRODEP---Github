<?php

namespace App\Http\Controllers;

use App\Imports\TutoriasImport;
use App\Models\Tutorias;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TutoriasExport;
use Illuminate\Support\Facades\Auth;

class TutoriasController extends Controller
{
    

    public function form()
{
    $user = auth()->user();

    // Obtener los tipos de tutoría únicos para el filtro en el modal
    $tipos_tutoria = Tutorias::distinct()->pluck('tipo_tutoria');
    
    // Obtener los maestros únicos
    $maestros = Tutorias::distinct()->pluck('tutor');
    
    // Inicializar el grupo
    $grupos = collect(); // Crear una colección vacía

    if ($user->level_id == 1) {
        // Para administradores: mostrar todos los registros y todos los grupos
        $data = Tutorias::paginate(10);
        $grupos = Tutorias::distinct()->pluck('grupo');
    } elseif ($user->level_id == 2) {
        // Para docentes: filtrar registros por nombre del docente y grupos relacionados
        $formattedName = $user->apellido_paterno . ' ' . $user->apellido_materno . ' ' . $user->name;
        $data = Tutorias::whereRaw('BINARY tutor = ?', [$formattedName])->paginate(10);
        $grupos = Tutorias::where('tutor', $formattedName)->distinct()->pluck('grupo');
    }

    return view('admin.pages.tutorias.index', compact('data', 'tipos_tutoria', 'grupos', 'maestros'));
}


    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Para importar registros, debe seleccionar un archivo.');
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx|file',
        ], [
            'file.mimes' => 'El archivo debe tener una extensión .xls o .xlsx.',
        ]);

        $file = $request->file('file');
        $periodo = $this->getPeriodoEscolar($file); // Obtener el periodo del archivo

        // Verificar si el periodo ya existe en la base de datos
        if (Tutorias::where('periodo', $periodo)->exists()) {
            return redirect()->back()->with('error', 'El periodo ' . $periodo . ' ya existe en la base de datos. No se puede importar nuevamente.');
        }

        try {
            $import = new TutoriasImport();
            Excel::import($import, $file);

            $insertedRows = 0;
            foreach ($import->getData() as $data) {
                Tutorias::create($data);
                $insertedRows++;
            }

            return redirect()->back()->with('success', 'Se han importado ' . $insertedRows . ' registros correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function getPeriodoEscolar($file)
    {
        $excelReader = Excel::toArray([], $file)[0];
        return $excelReader[2][0] ?? null;
    }

    public function export(Request $request)
{
    // Obtener filtros
    $tipo_tutoria = $request->input('tipo_tutoria');
    $grupo = $request->input('grupo');
    $status = $request->input('status');
    $maestro = $request->input('maestro');

    // Crear la consulta base
    $query = Tutorias::query();

    // Aplicar filtros
    if ($tipo_tutoria) $query->where('tipo_tutoria', $tipo_tutoria);
    if ($grupo) $query->where('grupo', $grupo);
    if ($status) $query->where('estatus', $status);
    
    // Filtrar por maestro solo si el usuario es un maestro
    if (auth()->user()->level_id == 2) {
        $formattedName = auth()->user()->apellido_paterno . ' ' . auth()->user()->apellido_materno . ' ' . auth()->user()->name;
        $query->where('tutor', $formattedName);
    } elseif ($maestro && auth()->user()->level_id == 1) {
        $query->where('tutor', $maestro);
    }

    // Seleccionar solo los campos específicos
    $data = $query->get(['fecha_registro', 'tutor', 'tipo_tutoria', 'grupo', 'alumno', 'estatus', 'motivo', 'periodo']);

    return Excel::download(new TutoriasExport($data), 'tutorias.xlsx');
}

    



}
