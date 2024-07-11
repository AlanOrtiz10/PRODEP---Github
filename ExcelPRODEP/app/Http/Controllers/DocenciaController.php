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
        $user = auth()->user();

        if($user->level_id == 1){
            $data = Docencia::paginate(10);

        }
        elseif ($user->level_id == 2) {
            $formattedName = $user->apellido_paterno . ' ' . $user->apellido_materno . ' ' . $user->name;

            $data = Docencia::whereRaw('BINARY nombre_profesor = ?', [$formattedName])->paginate(10);
        }
        
        //dd($formattedName);
        // dd($data);

        return view('admin.pages.docencias.index', compact('data'));
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

    try {
        $periodo_escolar = $this->getPeriodoEscolar($file);

        // Función para obtener el periodo escolar limpio
        function cleanPeriodoEscolar($periodo)
        {
            // Si el periodo contiene "PERIODO ESCOLAR:", se limpia y se devuelve lo posterior
            if (strpos($periodo, 'PERIODO ESCOLAR:') === 0) {
                return trim(substr($periodo, strlen('PERIODO ESCOLAR:')));
            }
            return $periodo;
        }

        $periodo_escolar = cleanPeriodoEscolar($periodo_escolar);

        if (Docencia::where('periodo_escolar', $periodo_escolar)->exists()) {
            $filePath = $file->store('temp');
            return redirect()->back()->with('error', 'El periodo escolar ' . $periodo_escolar . ' ya existe en la base de datos. ¿Desea actualizar los registros?')
                ->with('update_option', true)
                ->with('file_path', $filePath)
                ->with('periodo_escolar', $periodo_escolar);
        }

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
    


    public function updateImportedData(Request $request)
    {
        $filePath = $request->input('file_path');
        $file = storage_path('app/' . $filePath);
        $periodo_escolar = $request->input('periodo_escolar');

        try {
            // Eliminar los registros existentes del periodo escolar
            Docencia::where('periodo_escolar', $periodo_escolar)->delete();

            // Reimportar los nuevos datos
            $import = new DocenciaImport();
            Excel::import($import, $file);

            $insertedRows = 0;
            foreach ($import->getData() as $data) {
                Docencia::create($data);
                $insertedRows++;
            }

            // Eliminar el archivo temporal
            unlink($file);

            return redirect()->back()->with('success', 'Se han actualizado ' . $insertedRows . ' registros correctamente.');
        } catch (\Exception $e) {
            $errorMessage = explode("<br>", $e->getMessage())[0];
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function cancelUpdate(Request $request)
    {
        $filePath = $request->input('file_path');
        $file = storage_path('app/' . $filePath);

        if (file_exists($file)) {
            unlink($file);
        }

        return redirect()->back()->with('success', 'El archivo temporal ha sido eliminado.');
    }

    protected function getPeriodoEscolar($file)
    {
        $import = new DocenciaImport();
        $excel = Excel::toArray($import, $file)[0];
    
        // Buscar el periodo escolar en las primeras filas del archivo
        $periodo_escolar = null;
        foreach ($excel as $row) {
            foreach ($row as $cell) {
                // Buscar en cada celda si contiene el patrón de periodo escolar
                if (preg_match('/(PERIODO ESCOLAR:)?\s*([A-Z]{3}-[A-Z]{3}\s*\d{4})/i', $cell, $matches)) {
                    $periodo_escolar = $matches[2];
                    break 2; // Romper ambos bucles si se encuentra el periodo escolar
                }
            }
        }
    
        if (!$periodo_escolar) {
            throw new \Exception('No se encontró el periodo escolar en el archivo.');
        }
    
        return $periodo_escolar;
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
