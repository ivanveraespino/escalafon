<tbody>

    @if(!empty($datos))
    @foreach ($datos as $p)
    <tr style="width: 100%;">
        <td>{{ $p->nombre }}</td>
        <td>{{ $p->cumple }}</td>
    </tr>
    @endforeach
    @else
    <div class="bd-callout bd-callout-info">
        <p><strong>No se encontraron datos!</strong></p>
    </div>
    @endif
</tbody>