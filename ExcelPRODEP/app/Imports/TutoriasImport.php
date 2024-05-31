<?php

namespace App\Imports;

use App\Models\Tutorias;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TutoriasImport implements ToCollection
{
    protected $data = [];

    public function collection(Collection $rows)
{
    // Obtener el periodo escolar desde la fila 3, columna A
    $periodo = $rows[2][0];

    // Inicializar una variable para llevar el conteo real de las filas
    $fila_real = 6;

    foreach ($rows->slice(5) as $key => $row) {
        // Obtener los datos de cada fila
        $fecha_registro = !empty($row[0]) ? Carbon::createFromFormat('d/m/Y', $row[0])->format('Y-m-d') : null;
        $tutor = $row[1];
        $tipo_tutoria = $row[2];
        $grupo = $row[3];
        $alumno = $tipo_tutoria === 'GRUPAL' ? null : $row[4]; // Asignar null si el tipo de tutoría es "GRUPAL"
        $estatus = $row[5];
        $motivo = $row[6];

        // Validar si hay datos nulos
        $errorMessages = [];
        if (empty($fecha_registro)) {
            $errorMessages[] = "La fecha de registro en la fila " . $fila_real . " está vacía.";
        }
        if (empty($tutor)) {
            $errorMessages[] = "El tutor en la fila " . $fila_real . " está vacío.";
        }
        if (empty($tipo_tutoria)) {
            $errorMessages[] = "El tipo de tutoría en la fila " . $fila_real . " está vacío.";
        }
        if (empty($grupo)) {
            $errorMessages[] = "El grupo en la fila " . $fila_real . " está vacío.";
        }
        if (empty($estatus)) {
            $errorMessages[] = "El estatus en la fila " . $fila_real . " está vacío.";
        }
        if (empty($motivo)) {
            $errorMessages[] = "El motivo en la fila " . $fila_real . " está vacío.";
        }
        if (empty($periodo)) {
            $errorMessages[] = "El periodo en la fila " . $fila_real . " está vacío.";
        }
        // Validar que el campo Alumno esté lleno solo si el Tipo de Tutoría no es "GRUPAL"
        if ($tipo_tutoria != 'GRUPAL' && empty($alumno)) {
            $errorMessages[] = "El campo Alumno en la fila " . $fila_real . " no puede estar vacío excepto para el Tipo de Tutoría 'GRUPAL'.";
        }

        // Si hay errores, lanzar una excepción con los mensajes
        if (!empty($errorMessages)) {
            $errorMessage = implode(" ", $errorMessages);
            throw new \Exception($errorMessage);
        }

        // Incrementar el contador de fila real
        $fila_real++;

        // Agregar datos válidos a la propiedad $data
        $this->data[] = [
            'fecha_registro' => $fecha_registro,
            'tutor' => $tutor,
            'tipo_tutoria' => $tipo_tutoria,
            'grupo' => $grupo,
            'alumno' => $alumno,
            'estatus' => $estatus,
            'motivo' => $motivo,
            'periodo' => $periodo,
        ];
    }
}


    public function getData()
    {
        return $this->data;
    }
}
