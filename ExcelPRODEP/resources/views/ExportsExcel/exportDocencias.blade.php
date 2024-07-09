<table>
    <thead>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8">UNIVERSIDAD TECNOLÓGICA DE HERMOSILLO</th>
        </tr>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8">REPORTE DE DOCENCIAS</th>
        </tr>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8"></th>
        </tr>
        <tr>
            <th colspan="8"></th>
        </tr>
        <tr style="font-weight: bold; text-align: center;">
            <th>Profesor</th>
            <th>Carrera</th>
            <th>Grupo</th>
            <th>Cuatrimestre</th>
            <th>Asignatura</th>
            <th>Alumnos</th>
            <th>Asesorias Mes</th>
            <th>Hrs. Semanales</th>
            <th>Periodo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($docencia as $row)
            <tr>
                <td>{{ $row->nombre_profesor }}</td>
                <td>{{ $row->nombre_carrera }}</td>
                <td>{{ $row->grupo }}</td>
                <td>{{ $row->cuatrimestre ? $row->cuatrimestre : 'No especificado' }}</td>
                <td>{{ $row->asignatura }}</td>
                <td>{{ $row->numero_alumnos }}</td>
                <td>{{ $row->asesorias_mes }}</td>
                <td>{{ $row->horas_semanales_curso }}</td>
                <td>{{ $row->periodo_escolar }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
