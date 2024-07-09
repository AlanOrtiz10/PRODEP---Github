<?php

namespace App\Exports;

use App\Models\Docencia;
use App\Models\Tutorias;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DocenciasExport implements FromView
{
    public function view(): View
    {
        return view('ExportsExcel.exportDocencias', [
            'docencia' => Docencia::all()
        ]);
    }
}
