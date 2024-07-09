<?php

namespace App\Exports;

use App\Models\Directores;
use App\Models\Docencia;
use App\Models\Tutorias;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DirectoresExport implements FromView
{
    public function view(): View
    {
        return view('ExportsExcel.exportDirectores', [
            'directores' => Directores::all()
        ]);
    }
}
