<table>
    <thead>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8">UNIVERSIDAD TECNOLÓGICA DE HERMOSILLO</th>
        </tr>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8">REPORTE DE DIRECTORES</th>
        </tr>
        <tr style="background-color: grey; font-weight: bold; text-align: center;">
            <th colspan="8"></th>
        </tr>
        <tr>
            <th colspan="8"></th>
        </tr>
        <tr style="font-weight: bold; text-align: center;">
            <th>Director</th>
            <th>Carrera</th>
            <th>Nivel</th>
        </tr>
    </thead>
    <tbody>
        @foreach($directores as $row)
            <tr>
                <td>{{ $row->director }}</td>
                <td>{{ $row->carrera }}</td>
                <td>{{ $row->nivel }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
