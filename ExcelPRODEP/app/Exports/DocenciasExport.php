<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DocenciasExport implements FromView
{
    protected $docencia;

    // Constructor para recibir los datos filtrados
    public function __construct($docencia)
    {
        $this->docencia = $docencia;
    }

    public function view(): View
    {
        return view('ExportsExcel.exportDocencias', [
            'docencia' => $this->docencia
        ]);
    }
}
