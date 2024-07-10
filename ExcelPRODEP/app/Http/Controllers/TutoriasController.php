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

    if($user->level_id == 1){
        $data = Tutorias::paginate(10);

    }
    elseif ($user->level_id == 2) {
        $formattedName = $user->apellido_paterno . ' ' . $user->apellido_materno . ' ' . $user->name;

        $data = Tutorias::whereRaw('BINARY tutor = ?', [$formattedName])->paginate(10);
    }

    
    
    
    //dd($formattedName);
    // dd($data);

    return view('admin.pages.tutorias.index', compact('data'));
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

    public function export()
    {
        return Excel::download(new TutoriasExport, 'tutorias.xlsx');
    }


}
