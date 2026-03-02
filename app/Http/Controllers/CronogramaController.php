<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tipodoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CronogramaVacaciones;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use App\Models\Personal;
use Illuminate\Support\Str;
use DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Settings; // Importante añadir esta

use App\Models\VacacionesRe;
use App\Models\Vinculo;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class CronogramaController extends Controller
{

    public function index(Request $request)
    {
        $df = CronogramaVacaciones::where('personal_id', $request->id)->get();
        return Datatables::of($df)->make(true);
    }

    public function show($id)
    {
        $c = DB::table('cronograma_vac')->where('id', $id)->first();
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Contrato no encontrado'], 404);
        }
    }

    public static function store(Request $request)
    {
        $data = $request->except('archivo', 'idvr');
        $vr = null;
        if ($request->has('idvo')) {
            $vr = CronogramaVacaciones::find((int) $request->idvo);
            if ($vr) {
                $data['personal_id'] = $vr->personal_id;
                $data['idvo'] = $vr->idvo !== null ? $vr->idvo : $vr->id;
            }
        }
        // Validación de los datos directamente
        $validator = Validator::make($data, [
            'personal_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $archivo = FileHelper::createArchivo($request, $data['personal_id'], "05");
        if ($archivo) {
            $data['archivo'] = $archivo->id;
        }
        $vacacion = CronogramaVacaciones::create($data);
        if ($vr) {
            $vr->update(['idvr' => $vacacion->id]);
            if ($vr->idvo == NULL) {
                $vr->update(['idvo' => $vr->id]);
            }
        } else {
            if ($vacacion->idvo === NULL) {
                $vacacion->update(['idvo' => $vacacion->id]);
            }
        }


        return response()->json($vacacion->toArray());
    }


    public static function guardarrepro(Request $request)
    {
        $request->validate([
            'personal_id' => 'required',
        ]);
        $vacacion = null;
        if ($request->has('idvr')) {
            $vacacion = CronogramaVacaciones::find($request->idvr);
        }
        if (!$vacacion) {
            $data['idvo'] = $archivo ? $archivo->id : null;
            $vacacion = CronogramaVacaciones::create($data);
        }
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "05");
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id;
            $vacacion = CronogramaVacaciones::create($data);
        } else {
            $data = $request->except('archivo');
            $vacacion = CronogramaVacaciones::create($data);
        }

        return response()->json($vacacion->toArray());
    }

    public function update($id, Request $request)
    {
        $t = CronogramaVacaciones::find($id);
        $data = $request->except('personal_id', 'desder', 'hastar', 'diasr', 'idvr', 'idvo');

        if ($t) {
            $archivo = FileHelper::updateArchivo($request, $t, "05");
            if ($archivo !== null) {
                $data['archivo'] = $archivo->id;
            }
            $t->update($data);

            return response()->json(['success' => 'Datos actualizados correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public function destroy($id)
    {
        $t = CronogramaVacaciones::find($id);
        $vr = CronogramaVacaciones::where("idvr", $t->id)->first();
        if ($vr) {
            $vr->update(['idvr' => null]);
        }
        if ($t) {
            if ($t->archivo) {
                Archivo::where('id', $t->archivo)->delete();
            }
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    //EXCEL CARGA
    public function importExcelCron(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        if ($request->hasFile('excelFile')) {
            $file = $request->file('excelFile');
            $noCargado = [];
            $rows = (new FastExcel)->import($file);

            // Validación previa de todos los campos
            foreach ($rows as $index => $line) {
                $fila = $index + 2; // Número de fila (ajustando el índice base 0 a base 1)
                // Validar que el DNI tenga exactamente 8 dígitos y sea numérico
                if (!preg_match('/^\d{8}$/', $line['DNI'])) {
                    $nombreCompleto = ($line['APELLIDO_PATERNO'] ?? ' ') . ' ' . ($line['APELLIDO_MATERNO'] ?? ' ') . ' ' . ($line['NOMBRES'] ?? 'Nombre no disponible');
                    $noCargado[] = "Fila $fila: " . $nombreCompleto . " (DNI inválido: " . $line['DNI'] . ")";
                    continue;
                }
                // Validar que el personal esté registrado
                $personal = Personal::where('nro_documento_id', $line['DNI'])->first();
                if (!$personal) {
                    $nombreCompleto = ($line['APELLIDO_PATERNO'] ?? ' ') . ' ' . ($line['APELLIDO_MATERNO'] ?? ' ') . ' ' . ($line['NOMBRES'] ?? 'Nombre no disponible');
                    $noCargado[] = "Fila $fila: " . $nombreCompleto . " (No registrado)";
                    continue;
                }

                //FAST EXCEL CONVIERTE AUTOMATICAMENTE A FORMATO FECHA
                // Validar FECHA INICIO y FECHA FIN con condicionales
                $fechaInicio = $line['FECHA INICIO'] ?? null;
                $fechaFin = $line['FECHA FIN'] ?? null;
                $diasDeclarados = intval($line['DIAS'] ?? 0);

                if ($fechaInicio instanceof \DateTimeImmutable) {
                    $fechaInicio = Carbon::instance($fechaInicio);
                } else {
                    // Si no es un objeto DateTimeImmutable, agregarlo a la lista de errores
                    $noCargado[] = "Fila $fila: FECHA INICIO no es un objeto DateTimeImmutable válido.";
                    continue;
                }

                if ($fechaFin instanceof \DateTimeImmutable) {
                    $fechaFin = Carbon::instance($fechaFin);
                } else {
                    // Si no es un objeto DateTimeImmutable, agregarlo a la lista de errores
                    $noCargado[] = "Fila $fila: FECHA FIN no es un objeto DateTimeImmutable válido.";
                    continue;
                }


                // Validar que FECHA FIN no sea anterior a FECHA INICIO
                if ($fechaFin->lt($fechaInicio)) {
                    $noCargado[] = "Fila $fila: La FECHA FIN ({$line['FECHA FIN']}) no puede ser anterior a la FECHA INICIO ({$line['FECHA INICIO']}).";
                    continue;
                }

                // Calcular la diferencia en días
                $diasCalculados = $fechaInicio->diffInDays($fechaFin) + 1; // +1 para incluir el día de inicio

                // Validar si los días calculados coinciden con los días declarados
                if ($diasCalculados !== $diasDeclarados) {
                    $noCargado[] = "Fila $fila: Los días calculados ($diasCalculados) no coinciden con los días declarados ($diasDeclarados).";
                    continue;
                }

                // Validar que los días calculados no excedan el límite máximo de 30 días
                if ($diasCalculados > 30) {
                    $noCargado[] = "Fila $fila: Los días calculados ($diasCalculados) exceden el límite máximo permitido (30 días).";
                    continue;
                }
            }

            // Si hay errores de validación, devolver respuesta y no proceder con la carga
            if (!empty($noCargado)) {
                return response()->json(['errors' => $noCargado, 'message' => 'Errores de validación encontrados. Por favor corrige los siguientes errores:'], 400);
            }

            // Si todas las validaciones pasaron, proceder con la importación
            foreach ($rows as $line) {
                $personal = Personal::where('nro_documento_id', $line['DNI'])->first();

                $cronv = CronogramaVacaciones::create([
                    'id_subida' => Carbon::now()->format('YmdHis'),
                    'personal_id' => $personal->id_personal,
                    'periodo' => $line['PERIODO'],
                    'mes' => json_encode([$line['MES']]),
                    'fecha_ini' => json_encode([Carbon::parse($line['FECHA INICIO'])->format('Y-m-d')]),
                    'fecha_fin' => json_encode([Carbon::parse($line['FECHA FIN'])->format('Y-m-d')]),
                    'dias' => json_encode([$line['DIAS']]),
                ]);

                $archivo = FileHelper::createArchivo($request, $personal->id_personal, "05");
                if ($archivo) {
                    $cronv->update(['archivo' => $archivo->id]);
                }
                if ($cronv) {
                    $cronv->update(['idvo' => $cronv->id]);
                }
            }

            return response()->json(['message' => 'Archivo importado exitosamente.'], 200);
        }

        return response()->json(['message' => 'No se pudo subir el archivo.'], 400);
    }

    public function cronogramarIndividual()
    {
        $trabajadores = DB::table('personal as p')
            ->select('p.*', 'v.id as vinculo', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen')
            ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->get();
        return view('vacaciones.cronogramar', array('personal' => $trabajadores));
    }

    public function consultarIndividuo($id)
    {
        $cronogramas = DB::table('personal  as p')
            ->select('v.id as vinculo', 'cr.id', 'cr.nombredoc', 'cr.nrodoc', 'cr.observaciones', 'cr.fecha_ini', 'cr.archivo', 'cr.estado', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen', 'cr.periodo', 'cr.mes')
            ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->join('cronograma_vac as cr', 'cr.idvinculo', '=', 'v.id')
            ->where('p.id_personal', $id)->get();
        $reporte = view('vacaciones.tablacronograma', array('cronogramas' => $cronogramas))->render();
        if ($cronogramas) {
            return response()->json([
                'rpta' => 'ok',
                'datos' => $reporte
            ]);
        } else {
            return response()->json([
                'rpta' => 'error',
                'mensaje' => 'No se encontró el individuo'
            ], 404);
        }
    }
    public function guardarIndividual(Request $request)
    {
        $datos = $request->input('datos');

        if ($request->input("datos") != null) {

            $cronogramas = json_decode($datos);
            foreach ($cronogramas as $item) {

                if ($item->cambio == 1) {

                    if ($item->id > 0) {

                        $cronograma = CronogramaVacaciones::find($item->id);
                        Log::info($cronograma->periodo);
                        $cronograma->periodo = $item->periodo;
                        $cronograma->nrodoc = $item->nrodoc;
                        $cronograma->nombredoc = $item->tipodoc;
                        $cronograma->observaciones = $item->observaciones;
                        $cronograma->fecha_ini = $item->inicio;
                        $cronograma->mes = $item->mes;
                        $cronograma->archivo = $item->archivo;
                        $cronograma->save();
                    } else {
                        $cronograma = new CronogramaVacaciones();
                        $cronograma->periodo = $item->periodo;
                        $cronograma->nrodoc = $item->nrodoc;
                        $cronograma->nombredoc = $item->nombredoc;
                        $cronograma->observaciones = $item->observaciones;
                        $cronograma->fecha_ini = $item->inicio;
                        $cronograma->mes = $item->mes;
                        $cronograma->archivo = $item->archivo;
                        $cronograma->save();
                    }
                }
                if ($item->cambio == 2) {
                    if ($item->id > 0) {
                        $cronograma = CronogramaVacaciones::find($item->id);
                        $cronograma->delete();
                    }
                }
            }
        }
        return 1;
    }


    public function cronogramarMasivo(int $periodo = null)
    {
        $tiposdoc = Tipodoc::all();
        $periodo = $periodo ?? Carbon::now()->year + 1;

        $cronogramados = DB::table('cronograma_vac')->where('periodo', $periodo)->pluck('idvinculo');
        $historial = DB::table('cronograma_vac')->where('periodo', $periodo - 1)->pluck('mes', 'idvinculo');
        $trabajadores = DB::table('personal as p')
            ->select('p.*', 'v.id as vinculo', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen')
            ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->whereRaw('? >= YEAR(v.fecha_ini)', [$periodo])->where(function ($query) use ($periodo) {
                $query->whereNull('v.fecha_fin')->orWhereRaw('? <= YEAR(v.fecha_fin)', [$periodo]);
            })
            ->whereNotIn('v.id', $cronogramados)
            ->get();
        // Agregar columna "mes" a cada trabajador según historial
        $trabajadores->transform(function ($trabajador) use ($historial) {
            $trabajador->mes = $historial[$trabajador->vinculo] ?? null;
            return $trabajador;
        });

        $documentos = DB::table('cronograma_vac')
            ->select('nombredoc', 'nrodoc')
            ->distinct()
            ->where('periodo', '=', $periodo)
            ->orderBy('nombredoc')
            ->orderBy('nrodoc')
            ->get();
        return view(
            'vacaciones.cronogramarMasivo',
            array(
                'trabajadores' => $trabajadores,
                'tiposdoc' => $tiposdoc,
                'periodo' => $periodo,
                'documentos' => $documentos
            )
        );
    }

    public function guardarCronogramaMasivo(Request $request)
    {
        $ids = $request->query('ids');
        $mes = $request->query('mes');
        $periodo = $request->query('periodo');
        $tipoDocumento = $request->query('tipodoc');
        $nroDocVac = $request->query('nrodoc');
        $observaciones = $request->query('observaciones');
        foreach ($ids as $vinculo) {
            $programacion = new CronogramaVacaciones();
            $programacion->idvinculo = $vinculo;
            $programacion->nombredoc = $tipoDocumento;
            $programacion->nrodoc = $nroDocVac;
            $programacion->periodo = $periodo;
            $programacion->dias = 30;
            $programacion->mes = $mes;
            //$programacion->fecha_ini=
            $programacion->observaciones = $observaciones;
            $programacion->save();
        }
        return response()->json(['rpta' => 'ok', 'mensaje' => 'Se ha guardado correctamente']);
    }

    public function generarVacacionesMasivo(int $periodo, string $mes)
    {
        $periodo = $periodo ?? Carbon::now()->year + 1;
        $mes = $mes ?? 'ENERO';
        $tiposdoc = Tipodoc::all();
        $registros = DB::table('cronograma_vac as c')
            ->select('c.*', 'v.*', 'p.*', 'r.nombre as regimen') // selecciona todas las columnas de las tres tablas
            ->join('vinculos as v', 'c.idvinculo', '=', 'v.id')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->join('personal as p', 'p.id_personal', '=', 'v.personal_id')
            ->where('c.periodo', $periodo)
            ->where('c.mes', $mes)
            ->get();
        return view(
            'vacaciones.generarVacacionesMasivo',
            array(
                'registros' => $registros,
                'periodo' => $periodo,
                'mes' => $mes,
                'tiposdoc' => $tiposdoc
            )
        );

    }

    public function generarWord(Request $request)
    {
        $datos = $request->input('datos');
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $tipodoc = $request->input('tipodoc');
        $inicio = $request->input('inicio');
        $anioActual = date('Y');
        // Establecer la configuración regional a español
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $meses = [
            "ENERO",
            "FEBRRO",
            "MARZO",
            "ABRIL",
            "MAYO",
            "JUNIO",
            "JULIO",
            "AGOSTO",
            "SETIEMBRE",
            "OCTUBRE",
            "NOVIEMBRE",
            "DICIEMBRE"
        ];

        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $dominio = $_SERVER['HTTP_HOST'];
        // Obtener la ruta base del dominio
        $rutaDominio = $protocolo . '://' . $dominio;
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // --- CONFIGURACIÓN DE IDIOMA PERÚ ---
// Establece el idioma predeterminado del documento a Español (Perú)
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('es-PE'));

        // --- 1. ENCABEZADO (Logos y Título) ---
        $header = $section->addHeader();
        $tableHeader = $header->addTable();
        $tableHeader->addRow();
        // Logo Izquierdo (Muni)
        $tableHeader->addCell(2000)->addImage($rutaDominio . '/img/logo_informe.png', ['width' => 60]);
        $cellMid = $tableHeader->addCell(6000);
        $cellMid->addText("MUNICIPALIDAD PROVINCIAL DE LA CONVENCIÓN", ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $cellMid->addText("OFICINA DE GESTIÓN DE RECURSOS HUMANOS", ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $cellMid->addText('"Año de la recuperación y consolidación de la economía peruana"', ['italic' => true, 'size' => 8], ['alignment' => Jc::CENTER]);
        // Logo Derecho (Escudo)
        // $tableHeader->addCell(2000)->addImage('ruta/logo_peru.png', ['width' => 60]);
        Log::debug('Variable:', ['data' => $datos]);


        foreach ($datos as $item) {
            // --- 2. CUERPO DEL MEMORÁNDUM ---
            $section->addTextBreak(1);
            $section->addText("$tipodoc N° $inicio-$anioActual-OGRH-MPLC", ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);
            $tableInfo = $section->addTable();
            $styleInfo = ['size' => 10];
            $rows = [
                ['DE', ': '],
                ['', '  Jefe de la Oficina de Gestión de Recursos Humanos-MPLC'],
                ['A', ': '],
                ['C/C', ': '],
                ['ASUNTO', ': SE OTORGA VACACIONES PENDIENTES DEL PERIODO ' . $periodo . 'PROGRAMADOS PARA EL MES DE' . $mes],
                ['FECHA', ': QUILLABAMBA, ' . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y')]
            ];
            foreach ($rows as $row) {
                $tableInfo->addRow();
                $tableInfo->addCell(2000)->addText($row[0], ['bold' => true] + $styleInfo);
                $tableInfo->addCell(7000)->addText($row[1], $styleInfo);
            }
            $section->addTextBreak(1);
            // --- 3. CONTENIDO PRINCIPAL ---
            $textRun = $section->addTextRun(['alignment' => Jc::BOTH]);
            $textRun->addText("Previo un cordial saludo me dirijo a Ud., a efectos de comunicarle en atención del documento de la referencia... ");
            $textRun = $section->addTextRun(['alignment' => Jc::BOTH]);
            $textRun->addText("En el marco del artículo 2° del decleto legislativo N° 1405, a travez del cual se otorga a los servidores públicos entre otros derechos, el derecho al descanso vacacional remunerado durante 30 días naturales y se disfruta permanentemente de forma efectiva e ininterrumpida, la oportunidad al descanso vacacional se fija de común acuerdo entre el servidor y la entidad; a falta de acuerdo decide la entidad y está condicionada a que el servidor cumpla el record vacacional establecido. Ademas se contabiliza los días de: licencias y permisos,  que se resumen de la siguiente manera:");


            $licencias = DB::table('licencias as l')
                ->select('l.*') // selecciona todas las columnas de las tres tablas

                ->where('l.idvinculo', $item['idvinculo'])
                ->where('l.periodo', $periodo - 1)
                ->where('l.acuentavac', 1)
                ->get();


            // Crear un nuevo TextRun para mantener consistencia
            $textRun = $section->addTextRun(['alignment' => Jc::BOTH]);
            $textRun->addText('Licencias registradas del periodo ' . ($periodo - 1), ['bold' => true, 'size' => 12]);

            // Crear tabla en Word
            if ($licencias->count() > 0) {
                $table = $section->addTable([
                    'borderSize' => 6,
                    'borderColor' => '000000',
                    'cellMargin' => 80,
                ]);

                // Encabezados de la tabla (ajusta según tus columnas)
                $table->addRow();
                $table->addCell(2000)->addText('Documento');
                $table->addCell(3000)->addText('Motivo');
                $table->addCell(3000)->addText('Fecha Inicio');
                $table->addCell(3000)->addText('Fecha Fin');
                $table->addCell(3000)->addText('Duración');


                // Filas con datos
                foreach ($licencias as $licencia) {
                    $table->addRow();
                    $table->addCell(2000)->addText($licencia->nombredoc . ' ' . $licencia->nrodoc);
                    $table->addCell(3000)->addText($licencia->descripcion);
                    $table->addCell(3000)->addText($licencia->fecha_inicio);
                    $table->addCell(3000)->addText($licencia->fecha_fin);
                    // Construir texto de días, meses y años
                    $nrodias = $licencia->dias > 0 ? $licencia->dias . ' día(s) ' : '';
                    $nromeses = $licencia->mes > 0 ? $licencia->mes . ' mes(es) ' : '';
                    $nroanios = $licencia->anio > 0 ? $licencia->anio . ' año(s)' : '';

                    $duracion = trim($nrodias . $nromeses . $nroanios);

                    // Agregar a la celda
                    $table->addCell(3000)->addText($duracion);

                }
            } else {
                $section->addText("No existen licencias registradas para este vínculo en el periodo indicado.");
            }


            $permisos = DB::table('permisos as p')
                ->select('p.*') // selecciona todas las columnas de las tres tablas

                ->where('p.idvinculo', $item['idvinculo'])
                ->where('p.periodo', $periodo - 1)
                ->where('p.acuentavac', 1)
                ->get();
            // Crear un nuevo TextRun para mantener consistencia
            $textRun = $section->addTextRun(['alignment' => Jc::BOTH]);
            $textRun->addText('Permisos registrados del periodo ' . ($periodo - 1), ['bold' => true, 'size' => 12]);

            // Crear tabla en Word
            if ($permisos->count() > 0) {
                $table = $section->addTable([
                    'borderSize' => 6,
                    'borderColor' => '000000',
                    'cellMargin' => 80,
                ]);

                // Encabezados de la tabla (ajusta según tus columnas)
                $table->addRow();
                $table->addCell(2000)->addText('Documento');
                $table->addCell(3000)->addText('Fecha Inicio');
                $table->addCell(3000)->addText('Fecha Fin');
                $table->addCell(3000)->addText('Motivo');
                $table->addCell(3000)->addText('Duración');

                // Filas con datos
                foreach ($permisos as $permiso) {
                    $table->addRow();
                    $table->addCell(2000)->addText($permiso->idpermiso);
                    $table->addCell(3000)->addText($permiso->fecha_inicio);
                    $table->addCell(3000)->addText($permiso->fecha_fin);
                    $table->addCell(3000)->addText($permiso->descripcion);
                    // Construir texto de días, meses y años
                    $nrodias = $permiso->dias > 0 ? $permiso->dias . ' día(s) ' : '';
                    $nromeses = $permiso->mes > 0 ? $permiso->mes . ' mes(es) ' : '';
                    $nroanios = $permiso->anio > 0 ? $permiso->anio . ' año(s)' : '';

                    $duracion = trim($nrodias . $nromeses . $nroanios);

                    // Agregar a la celda
                    $table->addCell(3000)->addText($duracion);
                }
            } else {
                $section->addText("No existen permisos registrados para este vínculo en el periodo indicado.");
            }
            // --- 4. CIERRE Y FIRMA ---
            $section->addTextBreak(2);
            $section->addText("Atentamente,", null, ['alignment' => Jc::CENTER]);
            $section->addTextBreak(3);
            $section->addText("__________________________", null, ['alignment' => Jc::CENTER]);
            $section->addText("JEFE DE RECURSOS HUMANOS", ['size' => 9], ['alignment' => Jc::CENTER]);
            // Insertar salto de página
            $section->addPageBreak();
            $inicio++;
        }

        $fileName = 'Documento_' . now()->format('YmdHis') . '.docx';
        $tempPath = storage_path('app/' . $fileName);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        // Validar que se subió un archivo
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt',
        ]);

        $periodo = $request->input('periodo');
        $mesVacaciones = $request->input('mes-vacaciones');
        $tipoDocVac = $request->input('tipo-doc-vac');
        $nroDocVac = $request->input('nro-doc-vac');

        // Si se subió archivo CSV
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $ruta = $archivo->store('cronogramas'); // guarda en storage/app/cronogramas
        }

        // Leer archivo CSV
        $path = $request->file('archivo')->getRealPath();
        $file = fopen($path, 'r');

        // Saltar encabezado si existe
        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ';')) !== FALSE) {
            // $data es un array con cada columna
            Log::debug('Variable:', ['data' => $data[0]]);
            $personal = Personal::where(['nro_documento_id' => $data[0]])->first();

            $vinculo = DB::table('vinculos')
                ->select('id')
                ->where('personal_id', $personal->id_personal)
                ->where('fecha_ini', '<', DB::raw('GETDATE()'))
                ->where(function ($query) {
                    $query->where('fecha_fin', '>', DB::raw('GETDATE()'))
                        ->orWhereNull('fecha_fin');
                })
                ->where('fecha_ini', '<', '2027-01-01')
                ->first(); // solo el primer registro
            if ($vinculo) {
                DB::table('cronograma_vac')->insert([
                    'nrodoc' => $nroDocVac,
                    'periodo' => $periodo,
                    'mes' => $data[1],
                    'fecha_ini' => $data[2],
                    'fecha_fin' => $data[3],
                    'dias' => 30,
                    'nombredoc' => $tipoDocVac,
                    'idvinculo' => $vinculo->id,
                ]);
            }

        }

        fclose($file);

        return back()->with('success', 'Archivo procesado y licencias guardadas correctamente.');
    }


}
