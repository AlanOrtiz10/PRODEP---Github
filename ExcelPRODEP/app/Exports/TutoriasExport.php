<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class TutoriasExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Fecha Registro',
            'Tutor',
            'Tipo de Tutoría',
            'Grupo',
            'Alumno',
            'Estatus',
            'Motivo',
            'Periodo',
        ];
    }
}
