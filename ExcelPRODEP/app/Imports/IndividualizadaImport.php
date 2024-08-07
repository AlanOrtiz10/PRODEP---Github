<?php

namespace App\Imports;

use App\Models\Individualizada;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class IndividualizadaImport implements ToCollection
{
    protected $data = [];

    public function collection(Collection $rows)
    {
        // Empezamos en la fila 2 (índice 1), asumiendo que la fila 1 (índice 0) es el encabezado
        $rows = $rows->slice(1);

        // Inicializar una variable para llevar el conteo real de las filas
        $fila_real = 2;

        foreach ($rows as $row) {
            // Asignamos los valores de cada columna
            $carrera = trim($row[0]);
            $asesor_academico = trim($row[1]);
            $periodo_escolar = trim($row[2]);
            $matricula = trim($row[3]);
            $alumno_nombre = trim($row[4]);
            $nombre_estadia = trim($row[5]);

            // Validar si hay datos nulos
            $errorMessages = [];
            if (empty($carrera)) {
                $errorMessages[] = "El campo 'Carrera' está vacío en la fila " . $fila_real . ".";
            }
            if (empty($asesor_academico)) {
                $errorMessages[] = "El campo 'Asesor Académico' está vacío en la fila " . $fila_real . ".";
            }
            if (empty($periodo_escolar)) {
                $errorMessages[] = "El campo 'Periodo Escolar' está vacío en la fila " . $fila_real . ".";
            }
            if (empty($matricula)) {
                $errorMessages[] = "El campo 'Matrícula' está vacío en la fila " . $fila_real . ".";
            }
            if (empty($alumno_nombre)) {
                $errorMessages[] = "El campo 'Nombre del Alumno' está vacío en la fila " . $fila_real . ".";
            }
            if (empty($nombre_estadia)) {
                $errorMessages[] = "El campo 'Nombre de la Estadia' está vacío en la fila " . $fila_real . ".";
            }

            if (!empty($errorMessages)) {
                $errorMessage = implode("<br>", $errorMessages);
                throw new \Exception($errorMessage);
            }

            // Agregar datos válidos a la propiedad $data
            $this->data[] = [
                'carrera' => $carrera,
                'asesor_academico' => $asesor_academico,
                'periodo_escolar' => $periodo_escolar,
                'matricula' => $matricula,
                'alumno_nombre' => $alumno_nombre,
                'nombre_estadia' => $nombre_estadia,
            ];

            // Incrementar el contador de fila real
            $fila_real++;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function toJson()
    {
        return json_encode($this->data);
    }
}
