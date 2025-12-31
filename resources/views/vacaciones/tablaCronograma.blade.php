@php
$cont=1;
$arrayCronogramas=[];
foreach($cronogramas as $cron){

array_push($arrayCronogramas, [
"cont"=> $cont,
"id"=> $cron->id,
"cambio" => 0,
"periodo"=>$cron->periodo,
"mes" => $cron->mes,
"inicio"=>$cron->fecha_ini,
"tipodoc" => $cron->nombredoc,
"nrodoc" => $cron->nrodoc,
"observaciones" => $cron->observaciones,
"archivo" => $cron->archivo,
"estado"=>$cron->estado
]);
$cont++;
}
@endphp
<input type="hidden" name="datos-individuo" id="datos-individuo" value="{{json_encode($arrayCronogramas)}}">
<div id="tabla-registro">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Periodo</th>
                <th scope="col">Mes</th>
                <th scope="col">Inicio</th>
                <th scope="col">Documento</th>
                <th scope="col">Días</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
            @php
            $cont = 1;
            @endphp
            @foreach($cronogramas as $item)
            <th scope="row">{{$item->periodo}}</th>
            <th>{{$item->mes}}</th>
            <th>{{$item->fecha_ini ?? '-'}}</th>
            <th>{{$item->nombredoc ?? '-'}}: {{$item->nrodoc ?? '-'}}</th>
            <th>30 días</th>
            <th>
                <a onClick="editarRegistro({{ $cont }})" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                <a onClick="eliminarRegistro({{ $cont }})" class="btn btn-outline-danger"><i class="fas fa-edit"></i></a>
            </th>
            @php
            $cont = $cont + 1;
            @endphp
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-center">
<button type="submit" style="background-color: #1e8e3e; border-color: #1e8e3e;" onclick="guardarTodo()" class="btn btn-success " id="guardar-edicion"><i class="fas fa-save"></i>GUARDAR TODO</button>
</div>