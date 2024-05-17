<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DocenciaImport implements ToCollection
{
    protected $data = [];

    public function collection(Collection $rows)
    {
        // Obtener el periodo escolar (cuatrimestre) desde la fila 3, columna A
        $periodo_escolar = $rows[2][0];

        // Validar si el periodo escolar está vacío y mostrar un mensaje de error
        if (empty($periodo_escolar)) {
            throw new \Exception('El periodo escolar está vacío.');
        }

        // Obtener los datos de las filas 6 en adelante
        $maestros = $rows->slice(5)->pluck(0)->toArray();
        $carreras = $rows->slice(5)->pluck(1)->toArray();
        $materias = $rows->slice(5)->pluck(2)->toArray();
        $grupos = $rows->slice(5)->pluck(3)->toArray();
        $num_alumnos = $rows->slice(5)->pluck(5)->toArray();
        $asesorias_mes = $rows->slice(5)->pluck(7)->toArray();
        $horas_semanales_curso = $rows->slice(5)->pluck(6)->toArray();

        // Validar si hay datos nulos
        $errorMessages = [];
        for ($i = 0; $i < count($maestros); $i++) {
            if (empty($maestros[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que el nombre del profesor está vacío.";
            }
            if (empty($carreras[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que el nombre de la carrera está vacío.";
            }
            if (empty($grupos[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que el grupo está vacío.";
            }
            if (empty($materias[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que la asignatura está vacía.";
            }
            if (empty($num_alumnos[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que el número de alumnos está vacío.";
            } elseif (!is_numeric($num_alumnos[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que el número de alumnos debe ser un valor numérico.";
            }
            if (empty($asesorias_mes[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que las asesorías por mes están vacías.";
            } elseif (!is_numeric($asesorias_mes[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que las asesorías por mes debe ser un valor numérico.";
            }
            if (empty($horas_semanales_curso[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que las horas semanales de curso están vacías.";
            } elseif (!is_numeric($horas_semanales_curso[$i])) {
                $errorMessages[] = "En el registro " . ($i + 6) . " hubo un error ya que las horas semanales de curso debe ser un valor numérico.";
            }
        }

        // Si hay errores, devuelve un mensaje de error y termina la importación
        if (!empty($errorMessages)) {
            $errorMessage = implode("<br>", $errorMessages);
            throw new \Exception($errorMessage);
        }

        // Agregar los datos a la propiedad $data
        for ($i = 0; $i < count($maestros); $i++) {
            $this->data[] = [
                'nombre_profesor' => $maestros[$i],
                'nombre_carrera' => $carreras[$i],
                'nombre_director_carrera' => 'Alan Ortiz',
                'cuatrimestre' => null, 
                'horas_extras_mes' => null, 
                'grupo' => $grupos[$i],
                'asignatura' => $materias[$i],
                'numero_alumnos' => $num_alumnos[$i],
                'asesorias_mes' => $asesorias_mes[$i],
                'horas_semanales_curso' => $horas_semanales_curso[$i],
                'periodo_escolar' => $periodo_escolar,
            ];
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
