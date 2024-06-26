<?php

namespace App\Exports;

use App\Models\Tutorias;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TutoriasExport implements FromView
{
    public function view(): View
    {
        return view('ExportsExcel.exportTutorias', [
            'tutorias' => Tutorias::all()
        ]);
    }
}
