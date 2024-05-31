<?php

namespace App\Imports;

use App\Models\Directores;
use App\Models\Docencia;
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
        $maestros = $rows->slice(5)->pluck(0)->map(function ($maestro) {
            // Limpiar el valor del nombre del profesor
            return trim($maestro);
        })->toArray();
        $carreras = $rows->slice(5)->pluck(1)->toArray();
        $materias = $rows->slice(5)->pluck(2)->toArray();
        $grupos = $rows->slice(5)->pluck(3)->toArray();
        $num_alumnos = $rows->slice(5)->pluck(5)->toArray();
        $asesorias_mes = $rows->slice(5)->pluck(7)->toArray();
        $horas_semanales_curso = $rows->slice(5)->pluck(6)->toArray();

        foreach ($maestros as $key => $maestro) {
            if (stripos($materias[$key], 'ESTADÍA') === false && stripos($materias[$key], 'ESTADIA INGENIERÍA') === false) {
                // Validar si hay datos nulos
                $errorMessages = [];
                if (empty($maestro)) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que el nombre del profesor está vacío.";
                }
                if (empty($carreras[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que el nombre de la carrera está vacío.";
                }
                if (empty($grupos[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que el grupo está vacío.";
                }
                if (empty($materias[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que la materia está vacía.";
                }
                if (empty($num_alumnos[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que el número de alumnos está vacío.";
                } elseif (!is_numeric($num_alumnos[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que el número de alumnos debe ser un valor numérico.";
                }
                if (empty($asesorias_mes[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que las asesorías por mes están vacías.";
                } elseif (!is_numeric($asesorias_mes[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que las asesorías por mes debe ser un valor numérico.";
                }
                if (empty($horas_semanales_curso[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que las horas semanales de curso están vacías.";
                } elseif (!is_numeric($horas_semanales_curso[$key])) {
                    $errorMessages[] = "En el registro " . ($key + 6) . " hubo un error ya que las horas semanales de curso debe ser un valor numérico.";
                }

                if (!empty($errorMessages)) {
                    $errorMessage = implode("<br>", $errorMessages);
                    throw new \Exception($errorMessage);
                }

                $cuatrimestre = $this->extractCuatrimestre($grupos[$key]);

                // Buscar el director de la carrera
                $director = Directores::where('carrera', $carreras[$key])->first();
                if (!$director) {
                    throw new \Exception("No se encontró un director para la carrera: " . $carreras[$key]);
                }

                // Agregar datos válidos a la propiedad $data
                $this->data[] = [
                    'nombre_profesor' => $maestro,
                    'nombre_carrera' => $carreras[$key],
                    'director_id' => $director->id,
                    'cuatrimestre' => $cuatrimestre,
                    'horas_extras_mes' => null,
                    'grupo' => $grupos[$key],
                    'asignatura' => $materias[$key],
                    'numero_alumnos' => $num_alumnos[$key],
                    'asesorias_mes' => $asesorias_mes[$key],
                    'horas_semanales_curso' => $horas_semanales_curso[$key],
                    'periodo_escolar' => $periodo_escolar,
                ];
            }
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

    // Función para extraer el cuatrimestre del grupo
    private function extractCuatrimestre($grupo)
    {
        // Utilizar una expresión regular para extraer el primer número del grupo
        preg_match('/(\d{1,2})/', $grupo, $matches);
        return isset($matches[1]) ? intval($matches[1]) : null;
    }
}
