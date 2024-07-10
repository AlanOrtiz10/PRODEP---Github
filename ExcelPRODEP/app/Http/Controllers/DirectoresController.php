<?php

namespace App\Http\Controllers;

use App\Exports\DirectoresExport;
use Illuminate\Http\Request;
use App\Models\Directores;
use Maatwebsite\Excel\Facades\Excel;

class DirectoresController extends Controller
{
    public function form()
    {
        $data = Directores::paginate(10); 
        return view('admin.pages.directores.index', compact('data'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'carrera' => 'required|string',
            'nivel' => 'required|string',
            'director' => 'required|string', 
        ]);
    
    
        $director = new Directores();
        $director->carrera = $data['carrera'];
        $director->nivel = $data['nivel'];
        $director->director = $data['director'];
        $director->save();
    
        return redirect()->route('directores')->with('success', 'Director agregado correctamente');
    }

    public function edit($id)
    {
        $director = Directores::findOrFail($id);
        return response()->json($director);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'carrera' => 'required|string',
            'nivel' => 'required|string',
            'director' => 'required|string',
        ]);

        $director = Directores::findOrFail($id);
        $director->update([
            'carrera' => $request->carrera,
            'nivel' => $request->nivel,
            'director' => $request->director,
        ]);

        return redirect()->route('directores')->with('success', 'Director actualizado correctamente');
    }


    public function destroy($id)
{
    $director = Directores::findOrFail($id);
    $director->delete();

    return redirect()->route('directores')->with('success', 'Director eliminado correctamente.');
}


    public function export()
    {
        return Excel::download(new DirectoresExport, 'directores.xlsx');
    }
}
