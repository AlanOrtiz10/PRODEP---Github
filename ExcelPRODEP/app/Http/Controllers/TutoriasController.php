<?php

namespace App\Http\Controllers;

use App\Imports\TutoriasImport;
use App\Models\Tutorias;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TutoriasController extends Controller
{
    

    public function form()
    {
        $data = Tutorias::paginate(10); 
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


}
