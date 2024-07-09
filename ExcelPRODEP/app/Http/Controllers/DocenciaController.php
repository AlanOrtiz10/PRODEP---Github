<?php

namespace App\Http\Controllers;

use App\Exports\DocenciasExport;
use App\Imports\DocenciaImport;
use App\Models\Docencia;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\TemplateProcessor;

class DocenciaController extends Controller
{
    public function form()
    {
        $data = Docencia::paginate(10); 
        $profesores = Docencia::select('nombre_profesor', 'nombre_carrera')->distinct()->get()->groupBy('nombre_profesor')->map(function ($profesor) {
            return $profesor->pluck('nombre_carrera')->unique();
        });
        return view('admin.pages.docencias.index', compact('data', 'profesores'));
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
        return $excelReader[2][0] ?? null; 
    }

    // Método para generar el documento
    public function generateDoc($id)
    {
        // Recuperar los datos de la base de datos
        $docencia = Docencia::find($id);
        if (!$docencia) {
            return redirect()->back()->with('error', 'Datos no encontrados.');
        }

        // Cargar la plantilla de Word
        $templateProcessor = new TemplateProcessor(storage_path('app/Docencia.docx'));

        // Reemplazar los marcadores de posición con los datos
        $templateProcessor->setValue('nombre_profesor', $docencia->nombre_profesor . ' ' . $docencia->apellido_profesor );
        $templateProcessor->setValue('nombre_carrera', $docencia->nombre_carrera);
        $templateProcessor->setValue('director_carrera', $docencia->director->director);

        // Guardar el archivo modificado
        $fileName = 'Docencia_' . $docencia->id . '.docx';
        $templateProcessor->saveAs(storage_path('app/public/' . $fileName));

        // Descargar el archivo
        return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
    }

    public function export()
    {
        return Excel::download(new DocenciasExport, 'docencia.xlsx');
    }




}
