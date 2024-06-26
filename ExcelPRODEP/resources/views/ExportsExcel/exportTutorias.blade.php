<table>
    <thead>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8">UNIVERSIDAD TECNOLÓGICA DE HERMOSILLO</th>
        </tr>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8">REPORTE DE TUTORIAS</th>
        </tr>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8"></th>
        </tr>
        <tr>
            <th colspan="8"></th>
        </tr>
        <tr style="font-weight: bold; text-align: center;">
            <th>Fecha</th>
            <th>Tutor</th>
            <th>Tipo</th>
            <th>Grupo</th>
            <th>Alumno(s)</th>
            <th>Estatus</th>
            <th>Motivo</th>
            <th>Periodo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tutorias as $tutoria)
            <tr>
                <td>{{ $tutoria->fecha_registro }}</td>
                <td>{{ $tutoria->tutor }}</td>
                <td>{{ $tutoria->tipo_tutoria }}</td>
                <td>{{ $tutoria->grupo }}</td>
                <td>{{ $tutoria->alumno }}</td>
                <td>{{ $tutoria->estatus }}</td>
                <td>{{ $tutoria->motivo }}</td>
                <td>{{ $tutoria->periodo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
