<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class IndividualizadaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $userLevel;

    public function __construct(Collection $data, $userLevel)
    {
        $this->data = $data;
        $this->userLevel = $userLevel;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        // Ajustar los encabezados de acuerdo con el nivel del usuario
        $headings = [
            'Expediente',
            'Alumno',
            'Proyecto',
            'Carrera',
            'Periodo',
        ];

        if ($this->userLevel == 1) {
            array_unshift($headings, 'Asesor Académico'); // Agregar encabezado solo para administradores
        }

        return $headings;
    }

    public function map($row): array
    {
        // Mapea los datos según el nivel del usuario
        $data = [
            $row->matricula,
            $row->alumno_nombre,
            $row->nombre_estadia,
            $row->carrera,
            $row->periodo_escolar,
        ];

        if ($this->userLevel == 1) {
            array_unshift($data, $row->asesor_academico); 
        }

        return $data;
    }
}
