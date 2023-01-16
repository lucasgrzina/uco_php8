<table>
    <tr>
        @foreach($header as $key => $title)
            <td>{{ $title }}</td>
        @endforeach
    </tr>

    @foreach($data as $row)
        <tr>
            @foreach($header as $key => $title)
                @if(isset($format[$key]))
                    <td>{{ $format[$key]((isset($row[$key]) ? $row[$key] : null),$row) }}</td>
                @else
                    <td>{{ $row[$key] }}</td>
                @endif
                
            @endforeach
        </tr>
    @endforeach
</table>
<table>
    <tr><td>Cantidad de registros:</td><td>{{ count($data) }}</td></tr>
</table>