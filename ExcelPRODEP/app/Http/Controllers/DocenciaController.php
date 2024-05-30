<?php

namespace App\Http\Controllers;

use App\Imports\DocenciaImport;
use App\Models\Docencia;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DocenciaController extends Controller
{
    public function form()
{
    $data = Docencia::paginate(10); 
    $profesores = Docencia::select('nombre_profesor', 'nombre_carrera')->distinct()->get()->groupBy('nombre_profesor')->map(function ($profesor) {
        return $profesor->pluck('nombre_carrera')->unique();
    });
    return view('formulario', compact('data', 'profesores'));
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
    $periodo_escolar = $this->getPeriodoEscolar($file); // Obtener el periodo escolar del archivo

    // Verificar si el periodo escolar ya existe en la base de datos
    if (Docencia::where('periodo_escolar', $periodo_escolar)->exists()) {
        return redirect()->back()->with('error', 'El periodo escolar ' . $periodo_escolar . ' ya existe en la base de datos. No se puede importar nuevamente.');
    }

    try {
        $import = new DocenciaImport();
        Excel::import($import, $file);

        $insertedRows = 0;
        foreach ($import->getData() as $data) {
            Docencia::create($data);
            $insertedRows++;
        }

        return redirect()->back()->with('success', 'Se han importado ' . $insertedRows . ' registros correctamente.');
    } catch (\Exception $e) {
        $errorMessage = explode("<br>", $e->getMessage())[0];
        return redirect()->back()->with('error', $errorMessage);
    }
}

private function getPeriodoEscolar($file)
{
    $excelReader = Excel::toArray([], $file)[0];
    return $excelReader[2][0] ?? null; // Obtener el periodo escolar desde la tercera fila, primera columna del archivo Excel
}

}
