
@php
    $meses = [
        1 => 'ENERO',
        2 => 'FEBRERO',
        3 => 'MARZO',
        4 => 'ABRIL',
        5 => 'MAYO',
        6 => 'JUNIO',
        7 => 'JULIO',
        8 => 'AGOSTO',
        9 => 'SETIEMBRE',
        10 => 'OCTUBRE',
        11 => 'NOVIEMBRE',
        12 => 'DICIEMBRE',
    ];
@endphp
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">DATOS PERSONALES</p>
<h5>{{$personal->Apaterno}} {{$personal->Amaterno}}, {{$personal->Nombres}}</h5>
<div class="container">
    <div class="row">
        <div class="col-6">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr class="p-0">
                        <td class="p-0"><small>Identificación:</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->id_identificacion}}: {{$personal->nro_documento_id}}</strong></td>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Fecha de Nacimiento:</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->FechaNacimiento ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Sexo</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->sexo=='M' ? 'MASCULINO':'FEMENINO'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>RUC</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->NroRuc ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Celular</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->NroCelular ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Correo</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->Correo ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Estado Civil</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->EstadoCivil ?? '-'}}</strong></th>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="col-6">
            <table class="table table-borderless table-sm">
                <tbody>

                    <tr class="p-0">
                        <td class="p-0"><small>Nro. Essalud</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->NroEssalud ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Centro de At. Essalud</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->CentroEssalud ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>AFP</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->afp ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Discapacidad</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->discapacidad ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Fuerzas Armadas</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->ffaa ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Ocupación</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->ocupacion ?? '-'}}</strong></th>
                    </tr>
                    <tr class="p-0">
                        <td class="p-0"><small>Tipo Personal</small></td>
                        <th scope="row" class="p-0"><strong>{{$personal->tipopersonal ?? '-'}}</strong></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@if ($vinculos->count()>0)
<hr style="height:4px; background-color:green; border:none;">

<p class="text-munilc mb-0">VÍNCULOS</p>
<table class="table table-sm small">
    <thead>
        <tr>
            <th>Cargo</th>
            <th>Área</th>
            <th>Denominación</th>
            <th>Condición Laboral</th>
            <th>Documento</th>
            <th>Legajo</th>
            <th>F. Inicio</th>
            <th>F. Cese</th>
            <th>Motivo</th>
            <th>Causal</th>
            <th>Documento Cese</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vinculos as $vinculo)
        <tr class="p-0">
            <td class="p-0">{{$vinculo->cargo ?? '-'}}</td>
            <td class="p-0">{{$vinculo->area ?? '-'}}</td>
            <td class="p-0">{{$vinculo->denominacion ?? '-'}}</td>
            <td class="p-0">{{$vinculo->regimen ?? '-'}} {{$vinculo->condicion ?? '-'}}</td>
            <td class="p-0">{{$vinculo->nombredocvin ?? '-'}} {{$vinculo->nro_doc ?? '-'}}</td>
            <td class="p-0">{{$vinculo->filea ?? ''}} : {{$vinculo->lomo ?? ''}}</td>
            <td class="p-0">{{$vinculo->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$vinculo->fecha_fin ?? '-'}}</td>
            <td class="p-0">{{$vinculo->motivofin ?? '-'}}</td>
            <td class="p-0">{{$vinculo->motivocese ?? '-'}}</td>
            <td class="p-0">{{$vinculo->nombredoccese ?? '-'}} {{$vinculo->nro_doc_fin ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($rotaciones->count()>0)
<hr style="height:4px; background-color:green; border:none;">

<p class="text-munilc mb-0">ROTACIONES</p>
<table class="table table-sm small">
    <thead>
        <tr>
            <th>Origen</th>
            <th>Cargo</th>
            <th>Área Destino</th>
            <th>Actividades</th>
            <th>Documento</th>
            <th>F. Inicio</th>
            <th>F. Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rotaciones as $rotacion)
        <tr class="p-0">
            <td class="p-0">{{$rotacion->origen ?? '-'}}</td>
            <td class="p-0">{{$rotacion->cargo ?? '-'}}</td>
            <td class="p-0">{{$rotacion->destino ?? '-'}}</td>
            <td class="p-0">{{$rotacion->denominacion ?? '-'}}</td>
            <td class="p-0">{{$rotacion->nombredocvin ?? '-'}} {{$vinculo->nro_doc ?? '-'}}</td>
            <td class="p-0">{{$rotacion->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$rotacion->fecha_fin ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($encargaturas->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">ENCARGATURAS</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Origen</th>
            <th>Cargo</th>
            <th>Área Destino</th>
            <th>Actividades</th>
            <th>Documento</th>
            <th>F. Inicio</th>
            <th>F. Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($encargaturas as $encargatura)
        <tr class="p-0">
            <td class="p-0">{{$encargatura->origen ?? '-'}}</td>
            <td class="p-0">{{$encargatura->cargo ?? '-'}}</td>
            <td class="p-0">{{$encargatura->destino ?? '-'}}</td>
            <td class="p-0">{{$encargatura->denominacion ?? '-'}}</td>
            <td class="p-0">{{$encargatura->nombredocvin ?? '-'}} {{$vinculo->nro_doc ?? '-'}}</td>
            <td class="p-0">{{$encargatura->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$encargatura->fecha_fin ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($licencias->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">LICENCIAS</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Periodo</th>
            <th>Motivo</th>
            <th>Documento</th>
            <th>Observaciones</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Duración</th>
            <th>A cuenta Vac.</th>
            <th>Con goce</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($licencias as $licencia)
        <tr class="p-0">
            <td class="p-0">{{$licencia->periodo}}</td>
            <td class="p-0">{{$licencia->descripcion ?? '-'}}</td>
            <td class="p-0">{{$licencia->nombredoc ?? '-'}} {{$vinculo->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$licencia->observaciones ?? '-'}}</td>
            <td class="p-0">{{$licencia->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$licencia->fecha_fin ?? '-'}}</td>
            <td class="p-0">{{$licencia->anio ?? '-'}}A {{$licencia->mes ?? '_'}}M {{$licencia->dias}}D</td>
            <td class="p-0">{{$licencia->acuentavac==0 ? 'NO':'SI'}}</td>
            <td class="p-0">{{$licencia->congoce==0 ? 'NO':'SI'}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($permisos->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">PERMISOS</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Periodo</th>
            <th>Descripcion</th>
            <th>Documento</th>
            <th>Observaciones</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Duración</th>
            <th>A cuenta Vac.</th>
            <th>Con goce</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($permisos as $permiso)
        <tr class="p-0">
            <td class="p-0">{{$permiso->periodo ?? '-'}}</td>
            <td class="p-0">{{$permiso->descripcion ?? '-'}}</td>
            <td class="p-0">{{$permiso->nombredoc ?? '-'}} {{$vinculo->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$permiso->observaciones ?? '-'}}</td>
            <td class="p-0">{{$permiso->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$permiso->fecha_fin ?? '-'}}</td>
            <td class="p-0">{{$permiso->anio ?? '-'}}A {{$permiso->mes ?? '_'}}M {{$permiso->dias}}D</td>
            <td class="p-0">{{$permiso->acuentavac==0 ? 'NO':'SI'}}</td>
            <td class="p-0">{{$permiso->congoce==0 ? 'NO':'SI'}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($compensaciones->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">COMPENSACIONES</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Compensación</th>
            <th>Documento</th>
            <th>Descripción</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Duración</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($compensaciones as $compensacion)
        <tr class="p-0">
            <td class="p-0">{{$compensacion->tipocom ?? '-'}}</td>
            <td class="p-0">{{$compensacion->nombredoc ?? '-'}} {{$compensacion->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$compensacion->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$compensacion->fecha_fin ?? '-'}}</td>
            <td class="p-0">{{$compensacion->dias ?? '-'}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($reconocimientos->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">RECONOCIMIENTOS</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Descripcion</th>
            <th>Documento</th>
            <th>F. reconocimiento</th>
            <th>Forma</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reconocimientos as $reconocimiento)
        <tr class="p-0">
            <td class="p-0">{{$reconocimiento->descripcion ?? '-'}}</td>
            <td class="p-0">{{$reconocimiento->nombredoc ?? '-'}} {{$reconocimiento->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$reconocimiento->fecharecon ?? '-'}}</td>
            <td class="p-0">{{$reconocimiento->forma==1 ? 'De tiempo':'De labores'}}</td>
            <td class="p-0">{{$reconocimiento->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$reconocimiento->fecha_fin ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($sanciones->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">SANCIONES</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Motivo</th>
            <th>Documento</th>
            <th>F. reconocimiento</th>
            <th>Forma</th>
            <th>Duración</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sanciones as $sancion)
        <tr class="p-0">
            <td class="p-0">{{$sancion->descripcion ?? '-'}}</td>
            <td class="p-0">{{$sancion->nombredoc ?? '-'}} {{$sancion->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$sancion->fechadoc ?? '-'}}</td>
            <td class="p-0">{{$sancion->tiposancion==1 ? 'De tiempo':'De labores'}}</td>
            <td class="p-0">{{$sancion->dias_san ?? '-'}}</td>
            <td class="p-0">{{$sancion->fecha_ini ?? '-'}}</td>
            <td class="p-0">{{$sancion->fecha_fin ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if($cronograma->count() >0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">CRONOGRAMA DE VACACIONES</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Periodo</th>
            <th>Mes</th>
            <th>Documento</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cronograma as $periodo)
        <tr class="p-0">
            <td class="p-0">{{$periodo->periodo ?? '-'}}</td>
            <td class="p-0">{{$periodo->mes[0] ?? '-'}}</td>
            <td class="p-0">{{$periodo->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$periodo->fecha_ini[0] ?? '-' }}</td>
            <td class="p-0">{{$periodo->fecha_fin[0] ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($vacaciones->count() >0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">VACACIONES</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Periodo</th>
            <th>Mes</th>
            <th>Documento</th>
            <th>Duración</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vacaciones as $vac)
        <tr class="p-0">
            <td class="p-0">{{$vac->periodo ?? '-'}}</td>
            <td>{{ isset($vac->mes) && $vac->mes >= 1 && $vac->mes <= 12 ? $meses[$vac->mes] : '-' }}</td>
            <td class="p-0">{{$vac->nombredoc ?? '-'}} {{$vac->nrodoc ?? '-'}}</td>
            <td class="p-0">{{$vac->dias ?? '-'}} </td>
            <td class="p-0">{{$vac->fecha_ini ?? '-' }}</td>
            <td class="p-0">{{$vac->fecha_fin ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($familiares->count() >0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">FAMILIARES</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Identificación</th>
            <th>Nombre</th>
            <th>Parentesco</th>
            <th>Fecha Nac.</th>
            <th>Celular</th>
            <th>Derecho Hab.</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($familiares as $familiar)
        <tr class="p-0">
            <td class="p-0">{{$familiar->docid ?? '-'}} {{$familiar->nroid ?? '-'}}</td>
            <td class="p-0">{{$familiar->nombres ?? ''}} {{$familiar->apaterno ?? ''}} {{$familiar->amaterno ?? ''}}</td>
            <td class="p-0">{{$familiar->parentesco ?? '-'}} </td>
            <td class="p-0">{{$familiar->fechanacimiento ?? '-'}} </td>
            <td class="p-0">{{$familiar->telefono ?? '-' }}</td>
            <td class="p-0">{{$familiar->derecho_habiente ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($estudios->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">ESTUDIOS</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Nivel</th>
            <th>Institución</th>
            <th>Especialidad</th>
            <th>Documento</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estudios as $estudio)
        <tr class="p-0">
            <td class="p-0">{{$estudio->nivel_educacion ?? '-'}}</td>
            <td class="p-0">{{$estudio->centroestudios ?? ''}} </td>
            <td class="p-0">{{$estudio->especialidad ?? '-'}} </td>
            <td class="p-0">{{$estudio->nombredoc ?? '-'}} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($especialidades->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">ESTUDIOS DE ESPECIALIZACIÓN</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Denominación</th>
            <th>Institución</th>
            <th>Documento</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Duración</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($especialidades as $estudio)
        <tr class="p-0">
            <td class="p-0">{{$estudio->nombre ?? '-'}}</td>
            <td class="p-0">{{$estudio->centroestudios ?? ''}} </td>
            <td class="p-0">{{$estudio->nombredoc ?? '-'}} </td>
            <td class="p-0">{{$estudio->fecha_ini ?? '-'}} </td>
            <td class="p-0">{{$estudio->fecha_fin ?? '-'}} </td>
            <td class="p-0">{{$estudio->horas ?? '-'}} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($colegiatura->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">COLEGIATURA</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Colegio</th>
            <th>Fecha</th>
            <th>Número</th>
            <th>Documento</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($colegiatura as $colegio)
        <tr class="p-0">
            <td class="p-0">{{$colegio->nombre_colegio ?? '-'}}</td>
            <td class="p-0">{{$colegio->fechadoc ?? ''}} </td>
            <td class="p-0">{{$colegio->nrodoc ?? '-'}} </td>
            <td class="p-0">{{$colegio->nombredoc ?? '-'}} </td>
            <td class="p-0">{{$colegio->estado==0 ? 'Habilitado':'Inhabilitado'}} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($experiencias->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">EXPERIENCIA LABORAL</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Tipo entidad</th>
            <th>Entidad</th>
            <th>Área</th>
            <th>Cargo</th>
            <th>Documento</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($experiencias as $exp)
        <tr class="p-0">
            <td class="p-0">{{$exp->tipo_entidad==1 ? 'PÚBLICA':'PRIVADA'}}</td>
            <td class="p-0">{{$exp->entidad ?? ''}} </td>
            <td class="p-0">{{$exp->area ?? '-'}} </td>
            <td class="p-0">{{$exp->cargo ?? '-'}} </td>
            <td class="p-0">{{$exp->nombredoc ?? '-'}} {{$exp->nrodoc ?? '-'}} </td>
            <td class="p-0">{{$exp->fecha_ini ?? '-'}} </td>
            <td class="p-0">{{$exp->fecha_fin ?? '-'}} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($idiomas->count()>0)
<hr style="height:4px; background-color:green; border:none;">
<p class="text-munilc mb-0">IDIOMAS</p>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>Idioma</th>
            <th>Nivel de Lectura</th>
            <th>Nivel de Habla</th>
            <th>Nivel de Esritura</th>
            <th>Documento</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($idiomas as $idioma)
        <tr class="p-0">
            <td class="p-0">{{$idioma->idioma ?? '-'}}</td>
            <td class="p-0">{{$idioma->lectura ?? ''}} </td>
            <td class="p-0">{{$idioma->habla ?? '-'}} </td>
            <td class="p-0">{{$idioma->escritura ?? '-'}} </td>
            <td class="p-0">{{$idioma->nombredoc ?? '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif