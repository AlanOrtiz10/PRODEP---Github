<?php

namespace App\Http\Controllers;

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

        if($user->level_id == 1){
            $data = Individualizada::paginate(10);

        }
        elseif ($user->level_id == 2) {
            $formattedName = $user->name . ' ' . $user->apellido_paterno . ' ' . $user->apellido_materno;

            $data = Individualizada::whereRaw('BINARY asesor_academico = ?', [$formattedName])->paginate(10);
        }
        

        return view('admin.pages.individualizada.index', compact('data'));
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
}
