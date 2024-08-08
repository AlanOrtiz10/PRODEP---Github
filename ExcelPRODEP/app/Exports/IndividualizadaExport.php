<?php

namespace App\Exports;

use App\Models\Individualizada;
use Maatwebsite\Excel\Concerns\FromCollection;

class IndividualizadaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Individualizada::all();
    }
}
