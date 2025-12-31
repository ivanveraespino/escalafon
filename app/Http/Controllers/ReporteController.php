<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Modalidad;
use App\Models\Employee;
use App\Models\Personal;
use App\Models\Domicilio;
use App\Models\Explaboral;
use App\Models\CargoEntidad;
use App\Models\Condicionlab;
use App\Models\Vinculo;
use App\Models\Estudios;
use App\Models\Regimen;
use App\Models\CondicionLaboral;
use App\Models\Familiares;
use App\Models\Archivo;
use App\Models\Tipodoc;
use App\Models\Idiomas;
use Carbon\Carbon;
use DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

   

class ReporteController extends Controller
{
    public function index()
    {
        $tdoc = DB::table('tipodoc')->get();
        return view('Reportes.index',compact('tdoc'));
    }
/*
    public function getPersonal_list(Request $request)
    {
        $search = $request->input('buscar'); // Parámetro de búsqueda
        $limit = $request->input('limit', 5); // Límite predeterminado
        $id = $request->input('id'); // Parámetro para buscar un ID específico
    
        $c = Personal::select(
                'id_personal as id',
                DB::raw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            )
            ->when($id, function ($query, $id) {
                // Si se proporciona un ID, filtrar solo por ese ID
                return $query->where('id_personal', $id);
            })
            ->when($search, function ($query, $search) {
                // Si hay una búsqueda, aplicar el filtro correspondiente
                return $query->where(
                    DB::raw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres)"),
                    'like',
                    "%{$search}%"
                );
            })/*
            ->when(!$id && !$search, function ($query) use ($limit) {
                // Si no hay búsqueda ni ID, aplicar el límite predeterminado
                return $query->limit($limit);
            })
            ->orderBy('nombre', 'asc') // Ordenar alfabéticamente
            ->get();
    
        return response()->json($c);
    }*/


    public function getPersonal_list(Request $request)
    {
        $search = $request->input('buscar'); // Parámetro de búsqueda
        $id = $request->input('id'); // Parámetro para buscar un ID específico
        $query = Personal::select(
            'id_personal as id',
            'Apaterno',
            'Amaterno',
            'Nombres'
        )
        ->when($id, function ($query, $id) {
            return $query->where('id_personal', $id);
        })
        ->when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('Apaterno', 'like', "%{$search}%")
                    ->orWhere('Amaterno', 'like', "%{$search}%")
                    ->orWhere('Nombres', 'like', "%{$search}%");
            });
        })
        ->orderBy('Apaterno', 'asc')
        ->get();
        // Formatea los datos para Select2
        $result = $query->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => "{$item->Apaterno} {$item->Amaterno} {$item->Nombres}"
            ];
        });
        return response()->json($result);
    }

    public function get_modalidad_list()
    {
        $c = Modalidad::select('id', 'nombre')
            ->orderBy('nombre', 'asc') // Ordenar por nombre en orden alfabético
            ->get();
        return response()->json($c);
    }

    public function getCargo_list(Request $request)
    {
            $search = $request->input('buscar'); // Obtener el término de búsqueda
            $cargos = Cargo::select('id', 'nombre')
            ->where('estado', 1) 
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', '%' . $search . '%'); 
            })
            ->orderBy('nombre', 'asc') // Ordenar por nombre en orden alfabético
            ->get();
    
        return response()->json($cargos);
    }

    public function getAreas_list(Request $request)
    {
        $search = $request->input('buscar');
        $id = $request->input('id'); 
    
        $area = Area::select('area.id', 'area.nombre', 'area.dependencia', 'a2.nombre as nombre_dep')
            ->join('area as a2', 'area.dependencia', '=', 'a2.id')
            ->where('area.estado', 1)
            ->when($search, function ($query, $search) {
                return $query->where('area.nombre', 'like', '%' . $search . '%');
            })
            ->when($id, function ($query, $id) {
                return $query->where('area.id', $id);
            })
            ->orderBy('area.nombre', 'asc')
            ->get();
    
        return response()->json($area);
    }
    
    public function getRegimen_list()
    {
        $c = Regimen::select('id as id', 'nombre')
            ->orderBy('nombre', 'asc') // Ordenar por nombre en orden alfabético
            ->get();
    
        return response()->json($c);
    }

    public function getCondicion_list()
    {
        $c = CondicionLaboral::select('id', 'nombre')
            ->orderBy('nombre', 'asc') // Ordenar por nombre en orden alfabético
            ->get();
    
        return response()->json($c);
    }

    //EVALUAR
    public function getMovimiento_list()
    {
        $c = DB::table('tipo_movimiento')->select('id', 'nombre')->get();
        return response()->json($c);
    }

    public function getMotivofin_list()
    {
        $c = DB::table('motivo_fin_vinculo')->select('id', 'nombre')->get();
        return response()->json($c);
    }
    
    public function getMeses()
    {
        $meses = [
            ['id' => 1, 'nombre' => 'ENERO'],
            ['id' => 2, 'nombre' => 'FEBRERO'],
            ['id' => 3, 'nombre' => 'MARZO'],
            ['id' => 4, 'nombre' => 'ABRIL'],
            ['id' => 5, 'nombre' => 'MAYO'],
            ['id' => 6, 'nombre' => 'JUNIO'],
            ['id' => 7, 'nombre' => 'JULIO'],
            ['id' => 8, 'nombre' => 'AGOSTO'],
            ['id' => 9, 'nombre' => 'SEPTIEMBRE'],
            ['id' => 10, 'nombre' => 'OCTUBRE'],
            ['id' => 11, 'nombre' => 'NOVIEMBRE'],
            ['id' => 12, 'nombre' => 'DICIEMBRE']
        ];

        return response()->json($meses);
    }


    public function generarReporte(Request $request)
    {
        $tipoReporte = $request->input('tipoReporte');
        $resultados = [];
        $encabezadosPersonalizados = [];
        $tituloReporte = "REPORTE DE ";
    
        if ($tipoReporte === 'personal') {

            $res = $this->reportePersonal($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];

            $tituloReporte .= "PERSONAL";
        } elseif ($tipoReporte === 'vinculo_laboral') {
            $res = $this->reporteVinculoLaboral($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "VÍNCULO LABORAL";
        } elseif ($tipoReporte === 'cronograma_vac') {
            $res = $this->reporteCronograma($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "CRONOGRAMA DE VACACIONES";
        }
        elseif ($tipoReporte === 'vacaciones') {
            $res = $this->reporteVacaciones($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "VACACIONES";
        }
        elseif ($tipoReporte === 'licencias') {
            $res = $this->reporteLicencias($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "LICENCIAS";
        }
        elseif ($tipoReporte === 'permisos') {
            $res = $this->reportePermisos($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "PERMISOS";
        }
        elseif ($tipoReporte === 'didasAdeudados') {
            $res = $this->ReporteDiasAdeudados($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "DIAS ADEUDADOS";
        }
        elseif ($tipoReporte === 'movimientos') {
            $res = $this->ReporteMovimientos($request);
            $resultados = $res['resultados'];
            $encabezadosPersonalizados = $res['encabezados'];
            $tituloReporte .= "MOVIMIENTOS";
        } 
        foreach ($request->except(['_token', 'tipoReporte']) as $key => $value) {
            if (!empty($value)) {
                $tituloReporte .= " - " . strtoupper(str_replace('_', ' ', $key)) . ": " . strtoupper($value);
            }
        }
    
        return $this->crearExcel($resultados, $tituloReporte, $encabezadosPersonalizados);
    }

    private function crearExcel($resultados, $tituloReporte, $encabezadosPersonalizados)
    {
        // Crear una nueva hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configurar estilo de los encabezados
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '1CC88A']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000']
                ]
            ]
        ];

        // Si no hay resultados, devolver una respuesta sin generar el Excel
            // Verificar si hay datos
            if ($resultados->isEmpty()) {
                $sheet->setCellValue('A1', 'NO HAY DATOS DISPONIBLES PARA GENERAR EL REPORTE');
                $sheet->mergeCells('A1:H1'); // Ajusta el rango según tus necesidades
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            } else {
        // Calcular la última columna basada en los encabezados personalizados
        $encabezados = array_keys((array)$resultados->first());
        $ultimaColumna = chr(ord('A') + count($encabezadosPersonalizados) - 1); // Calcular la última columna

        // Título del reporte
        $sheet->setCellValue('A1', strtoupper($tituloReporte));
        $sheet->mergeCells('A1:' . $ultimaColumna . '1'); // Ajusta el rango según la cantidad de columnas
        $sheet->getStyle('A1')->applyFromArray($headerStyle);
        $sheet->getStyle('A1')->getFont()->setSize(18);

        // Configurar encabezados de columna usando los nombres personalizados
        $columna = 'A';
        foreach ($encabezados as $encabezado) {
            if (isset($encabezadosPersonalizados[$encabezado])) {
                $sheet->setCellValue($columna . '2', strtoupper(str_replace('_', ' ', $encabezadosPersonalizados[$encabezado])));
                $columna++;
            }
        }

        // Aplicar estilo a los encabezados
        $sheet->getStyle('A2:' . $ultimaColumna . '2')->applyFromArray($headerStyle);

        // Añadir datos a la hoja de cálculo
        $row = 3;
        foreach ($resultados as $resultado) {
            $columna = 'A';
            foreach ($encabezados as $encabezado) {
                if (isset($encabezadosPersonalizados[$encabezado])) {
                    $sheet->setCellValue($columna . $row, $resultado->$encabezado);
                    $sheet->getStyle($columna . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($columna . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $columna++;
                }
            }
            $row++;
        }

        // Autoajustar el ancho de las columnas
        foreach (range('A', $ultimaColumna) as $columna) {
            $sheet->getColumnDimension($columna)->setAutoSize(true);
        }
    }

        // Guardar la hoja de cálculo en un archivo temporal
        $writer = new Xlsx($spreadsheet);
        ob_start(); // Iniciar el buffer de salida
        $writer->save('php://output'); // Guardar la hoja de cálculo en el buffer de salida
        $excelOutput = ob_get_clean(); // Obtener el contenido del buffer de salida

        $tituloReporte = preg_replace('/[^A-Za-z0-9_\-]/', '_', $tituloReporte); // Reemplaza caracteres especiales
        $fileName = $tituloReporte . '.xlsx';
        $filePath = storage_path('app/' . $fileName);
        $writer->save($filePath);


        return response($excelOutput)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"")
            ->header('Cache-Control', 'max-age=0');
        // Descargar el archivo
    }


    ///´REPORTES EN BASE A CONSULTAS

    private function reportePersonal(Request $request)
    {
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $cargo = $request->input('cargo');
        $oficina = $request->input('oficina');
        $inicio_vinculo = $request->input('inicio_vinculo');
        $fecha_inicio_desde = $request->input('fecha_inicio_desde');
        $fecha_inicio_hasta = $request->input('fecha_inicio_hasta');

        $laststudy = DB::table('estudios')
            ->select('personal_id', DB::raw('COALESCE(MIN(fecha_ini), \'0000-00-00\') as fecha_ini'), 'GradoAcademico')
            ->groupBy('personal_id', 'GradoAcademico');
        $oldestContract = DB::table('vinculos')
            ->select('personal_id', DB::raw('MIN(fecha_ini) as inicio_vinculo'))
            ->groupBy('personal_id');
        $recentContract = DB::table('vinculos as c1')
            ->select('c1.personal_id', 'c1.id_unidad_organica', 'c1.id_cargo', 'condicion_laboral.nombre as cl', 'regimen.nombre as r_nombre', 'area.nombre as oficina_nombre', 'cargo.nombre as cargo_nombre','c1.nro_doc','tipodoc.nombre as nom_td', 'c1.fecha_ini' )
            ->join('tipodoc', 'c1.id_tipo_documento', '=', 'tipodoc.id')
            ->join('area', 'c1.id_unidad_organica', '=', 'area.id')
            ->join('regimen', 'c1.id_regimen', '=', 'regimen.id')
            ->join('condicion_laboral', 'c1.id_condicion_laboral', '=', 'condicion_laboral.id')
            ->join('cargo', 'c1.id_cargo', '=', 'cargo.id')
            ->whereColumn('c1.fecha_ini', '=', DB::raw('(SELECT MAX(fecha_ini) FROM vinculos WHERE vinculos.personal_id = c1.personal_id)'))
            ->whereIn('c1.id', function($query) {
                $query->select('id')->from('vinculos')->whereColumn('vinculos.personal_id', 'c1.personal_id')->orderBy('fecha_ini', 'desc')->limit(1);
            })
            ->groupBy('c1.personal_id', 'c1.id_unidad_organica', 'c1.id_cargo', 'area.nombre', 'cargo.nombre', 'regimen.nombre', 'condicion_laboral.nombre','c1.fecha_ini','c1.nro_doc','tipodoc.nombre');
            
        $query = DB::table('personal')
            ->leftJoin('domicilio', 'personal.id_personal', '=', 'domicilio.personal_id')
            ->leftJoin('estudios', 'personal.id_personal', '=', 'estudios.personal_id')
            ->leftJoinSub($recentContract, 'rc', function($join) {
                $join->on('personal.id_personal', '=', 'rc.personal_id');
            })
            ->leftJoinSub($oldestContract, 'oc', function($join) {
                $join->on('personal.id_personal', '=', 'oc.personal_id');
            })
            ->leftJoinSub($laststudy, 'ls', function($join) {
                $join->on('personal.id_personal', '=', 'ls.personal_id');
            })
            ->select(
                'rc.r_nombre as regimen',
                'rc.cl as condicion',
                'personal.nro_documento_id',
                'personal.Apaterno',
                'personal.Amaterno',
                'personal.Nombres',
                DB::raw("CONCAT(personal.Apaterno, ' ', personal.Amaterno, ' ', personal.Nombres) as nombre_completo"),
                'personal.id_personal',
                'personal.FechaNacimiento',
                DB::raw("FORMAT(personal.FechaNacimiento, 'dd-MM-yyyy') as FechaNacimiento"),
                'personal.id_tipo_personal as estado',
                'ls.GradoAcademico',
                'domicilio.dactual',
                'personal.Correo',
                'personal.NroCelular',
                'rc.cargo_nombre as cargo',
                'rc.oficina_nombre as oficina',
                'rc.nom_td',
                'rc.nro_doc',
                'oc.inicio_vinculo',
                DB::raw("FORMAT(rc.fecha_ini, 'dd-MM-yyyy') as inicio_vinculo"),

            )
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('rc.r_nombre',$regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('rc.cl',$condicion);
            })
            ->when($cargo, function($query) use ($cargo) {
                return $query->where('rc.cargo_nombre',$cargo);
            })
            ->when($oficina, function($query) use ($oficina) {
                return $query->where('rc.oficina_nombre',$oficina);
            })
            ->when($fecha_inicio_desde, function($query) use ($fecha_inicio_desde) {
                return $query->where('rc.fecha_ini', '>=', $fecha_inicio_desde);
            })
            ->when($fecha_inicio_hasta, function($query) use ($fecha_inicio_hasta) {
                return $query->where('rc.fecha_ini', '<=', $fecha_inicio_hasta);
            })
            ->groupBy(
                'rc.r_nombre',
                'rc.cl',
                'personal.id_personal',
                'personal.nro_documento_id',
                'personal.Apaterno',
                'personal.Amaterno',
                'personal.Nombres',
                'personal.FechaNacimiento',
                'personal.id_tipo_personal',
                'personal.Correo',
                'personal.NroCelular',
                'domicilio.dactual',
                'ls.GradoAcademico',
                'rc.cargo_nombre',
                'rc.oficina_nombre',
                'rc.nom_td',
                'rc.nro_doc',
                'rc.fecha_ini',
                'oc.inicio_vinculo'
            );
        $resultados = $query->distinct()->get();
        $encabezadosPersonalizados = [
            'regimen' => 'regimen',
            'condicion' => 'condicion laboral',
            'nro_documento_id' => 'dni',
            'nombre_completo' => 'PERSONAL',
            'FechaNacimiento' => 'Fecha NACIMIENTO',
            'Correo' => 'Correo',
            'NroCelular' => 'N° CELULAR',
            'dactual' => 'DIRECCION',
            'GradoAcademico' => 'Grado Academico',
            'cargo' => 'CARGO',
            'oficina' => 'OFICINA',
            'nom_td' => 'TIPO DOCUMENTO',
            'nro_doc' => 'N° DOCUMENTO',

            'inicio_vinculo' => 'FECHA INGRESO',
        ];
        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }

    private function reporteVinculoLaboral(Request $request)
    {
        $cargo = $request->input('cargo');
        $oficina = $request->input('oficina');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $dni = $request->input('personal');
        $fecha_inicio_desde = $request->input('fecha_inicio_desde');
        $fecha_inicio_hasta = $request->input('fecha_inicio_hasta');
        $fecha_fin_desde = $request->input('fecha_fin_desde');
        $fecha_fin_hasta = $request->input('fecha_fin_hasta');

        $query = DB::table('vinculos as c')
            ->select(
                'p.nro_documento_id as personal_id',
                DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres) as nombre_completo"),
                'regimen.nombre as regimen',
                'condicion_laboral.nombre as condicion',
                'a.nombre as oficina_nombre',
                'ca.nombre as cargo_nombre',
                'c.fecha_ini',
                'c.fecha_fin',
                DB::raw("FORMAT(c.fecha_ini, 'dd-MM-yyyy') as fecha_ini"), 
                DB::raw("FORMAT(c.fecha_fin, 'dd-MM-yyyy') as fecha_fin") 
                )
            ->leftjoin('area as a', 'c.id_unidad_organica', '=', 'a.id')
            ->leftjoin('personal as p', 'c.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as ca', 'c.id_unidad_organica', '=', 'ca.id')
            ->leftJoin('regimen', 'c.id_regimen', '=', 'regimen.id')
            ->leftJoin('condicion_laboral', 'c.id_condicion_laboral', '=', 'condicion_laboral.id')
            ->when($cargo, function($query) use ($cargo) {
                return $query->where('ca.nombre',$cargo);
            })
            ->when($dni, function($query) use ($dni) {
                return $query->where(DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres)"),$dni);
            })
            ->when($oficina, function($query) use ($oficina) {
                return $query->where('a.nombre',$oficina);
            })
            ->when($fecha_inicio_desde, function($query) use ($fecha_inicio_desde) {
                return $query->where('c.fecha_ini', '>=', $fecha_inicio_desde);
            })
            ->when($fecha_inicio_hasta, function($query) use ($fecha_inicio_hasta) {
                return $query->where('c.fecha_ini', '<=', $fecha_inicio_hasta);
            })
            ->when($fecha_fin_desde, function($query) use ($fecha_fin_desde) {
                return $query->where('c.fecha_fin', '>=', $fecha_fin_desde);
            })
            ->when($fecha_fin_hasta, function($query) use ($fecha_fin_hasta) {
                return $query->where('c.fecha_fin', '<=', $fecha_fin_hasta);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('regimen.nombre',$regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('condicion_laboral.nombre',$condicion);
            })
            ->orderBy('c.fecha_ini', 'asc');
    
        $resultados = $query->distinct()->get();
    
        $encabezadosPersonalizados = [
            'personal_id' => 'DNI del Personal',
            'nombre_completo' => 'PERSONAL',
            'regimen' => 'REGIMEN',
            'condicion' => 'Condicion Laboral',
            'oficina_nombre' => 'oficina',
            'moda_nombre' => 'Modalidad',
            'cargo_nombre' => 'cargo',
            'fecha_ini' => 'Fecha de Ingreso',
            'fecha_fin' => 'Fecha de Cese'

        ];
    
        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }

    private function ReporteMovimientos(Request $request)
    {
        $cargo = $request->input('cargo');
        $oficina_destino = $request->input('oficina');
        $dni = $request->input('personal');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $movimiento = $request->input('movimiento');
        $fecha_rotacion_desde = $request->input('fecha_ini');
        $fecha_rotacion_hasta = $request->input('fecha_fin');
        $query = DB::table('movimientos as r')
            ->select(
                'p.nro_documento_id as personal_id',
                DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres) as nombre_completo"),
                'regimen.nombre as regimen',
                'condicion_laboral.nombre as condicion',
                'tipodes.nombre as tipo_movimiento',
                'area.nombre as oficina_d',
                'ca.nombre as cargo',
                'r.fecha_ini',
                'r.fecha_fin',
                DB::raw("FORMAT(r.fecha_ini, 'dd-MM-yyyy') as fecha_ini"), 
                DB::raw("FORMAT(r.fecha_fin, 'dd-MM-yyyy') as fecha_fin") 
            )
            ->leftJoin('area', 'r.oficina_d', '=', 'area.id')
            ->leftJoin('personal as p', 'r.personal_id', '=', 'p.id_personal')
            ->leftJoin('cargo as ca', 'r.cargo', '=', 'ca.id')
            //EVALUAR
            ->leftJoin('tipodoc as tipodes', 'r.tipo_movimiento', '=', 'tipodes.id')
            ->leftJoin('regimen', 'p.id_regimen', '=', 'regimen.id')
            ->leftJoin('condicion_laboral', 'p.id_regimen_modalidad', '=', 'condicion_laboral.id')
            ->when($cargo, function ($query) use ($cargo) {
                return $query->where('ca.nombre', $cargo);
            })
            ->when($movimiento, function ($query) use ($movimiento) {
                return $query->where('tipodes.nombre', $movimiento);
            })
            ->when($dni, function ($query) use ($dni) {
                return $query->where(DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres)"), $dni);
            })
            ->when($oficina_destino, function ($query) use ($oficina_destino) {
                return $query->where('area.nombre', $oficina_destino);
            })
            ->when($fecha_rotacion_desde, function ($query) use ($fecha_rotacion_desde) {
                return $query->where('r.fecha_ini', '>=', $fecha_rotacion_desde);
            })
            ->when($fecha_rotacion_hasta, function ($query) use ($fecha_rotacion_hasta) {
                return $query->where('r.fecha_fin', '<=', $fecha_rotacion_hasta);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('regimen.nombre',$regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('condicion_laboral.nombre',$condicion);
            })
            ->orderBy('r.fecha_ini', 'asc');

        $resultados = $query->distinct()->get();

        $encabezadosPersonalizados = [
            'personal_id' => 'DNI del Personal',
            'nombre_completo' => 'PERSONAL',
            'regimen' => 'REGIMEN',
            'condicion' => 'Condicion Laboral',
            'tipo_movimiento' => 'TIPO DE MOVIMIENTO',
            'oficina_d' => 'Oficina de Destino',
            'cargo' => 'Cargo',
            'fecha_ini' => 'Desde',
            'fecha_fin' => 'Hasta',
        ];

        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }

    
    private function reporteCronograma(Request $request)
    {
        $periodo = $request->input('periodo');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $mes = $request->input('mes');
        $dni = $request->input('personal');
        $fecha_inicio_desde = $request->input('fecha_inicio_desde');
        $fecha_inicio_hasta = $request->input('fecha_inicio_hasta');

        $query = DB::table('cronograma_vac as cv')
            ->select(
                'p.id_personal',
                'regimen_vinculo.nombre as regimen',
                DB::raw("p.nro_documento_id as nro_dni"),
                DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres) as nombre"),
                'condicion_vinculo.nombre as condicion',
                'cv.periodo',
                'cv.mes',
                'cv.idvr',
                'cv.fecha_ini',
                'cv.fecha_fin',
                'cv.dias',
            )
            ->leftjoin('personal as p', 'cv.personal_id', '=', 'p.id_personal')
            ->leftJoinSub(
                DB::table('vinculos as v')
                    ->select('v.personal_id', 'v.id_unidad_organica', 'v.id_cargo', 'v.id_regimen', 'v.id_condicion_laboral')
                    ->whereIn('v.id', function ($query) {
                        $query->select('id')->from('vinculos')->whereColumn('vinculos.personal_id', 'v.personal_id')->orderBy('fecha_ini', 'desc')->limit(1);
                    }),

                'last_vinculo',
                'last_vinculo.personal_id',
                '=',
                'cv.personal_id'
            )
            ->leftJoin('regimen as regimen_vinculo', 'last_vinculo.id_regimen', '=', 'regimen_vinculo.id')
            ->leftJoin('condicion_laboral as condicion_vinculo', 'last_vinculo.id_condicion_laboral', '=', 'condicion_vinculo.id')
            ->whereNull('cv.idvr') //PARA VALIDAR SOLO PROGRAMACIONES ACTUALES
            ->when($periodo, function($query) use ($periodo) {
                return $query->where('cv.periodo', $periodo);
            })
            ->when($mes, function($query) use ($mes) {
                return $query->where('cv.mes', 'LIKE', '%"' . $mes . '"%');
            })
            ->when($dni, function($query) use ($dni) {
                return $query->where(DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres)"),$dni);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('regimen_vinculo.nombre',$regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('condicion_vinculo.nombre',$condicion);
            })
            ->when($fecha_inicio_desde, function($query) use ($fecha_inicio_desde) {
                return $query->where('cv.fecha_ini', '>=', $fecha_inicio_desde);
            })
            ->when($fecha_inicio_hasta, function($query) use ($fecha_inicio_hasta) {
                return $query->where('cv.fecha_ini', '<=', $fecha_inicio_hasta);
            })
  
            ->orderBy('cv.fecha_ini', 'asc');
    
        $resultados = $query->distinct()->get();
    
        // Procesar el campo 'mes' para convertirlo a una cadena legible
        foreach ($resultados as $row) {
            if (!empty($row->mes)) {
                $meses = json_decode($row->mes, true);
                if (is_array($meses)) {
                    $row->mes = implode(", ", $meses); 
                }
            }
        }
        foreach ($resultados as $row) {
            if (!empty($row->dias)) {
                $dias = json_decode($row->dias, true);
                if (is_array($dias)) {
                    $row->dias = implode(", ", $dias); 
                }
            }
        }
        foreach ($resultados as $row) {
            if (!empty($row->fecha_ini)) {
                $fecha_ini = json_decode($row->fecha_ini, true);
                if (is_array($fecha_ini)) {
                    $row->fecha_ini = implode(", ", $fecha_ini); 
                }
            }
        }
        foreach ($resultados as $row) {
            if (!empty($row->fecha_fin)) {
                $fecha_fin = json_decode($row->fecha_fin, true);
                if (is_array($fecha_fin)) {
                    $row->fecha_fin = implode(", ", $fecha_fin); 
                }
            }
        }

        $encabezadosPersonalizados = [
            'nro_dni' => 'DNI',
            'nombre' => 'personal',
            'regimen' => 'REGIMEN',
            'condicion' => 'Condicion Laboral',
            'periodo' => 'Período',
            'mes' => 'Mes',
            'fecha_ini' => 'Desde',
            'fecha_fin' => 'Hasta',
            'dias' => 'Dias',
        ];
    
        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }

    public function ReporteDiasAdeudados(Request $request)
    {
        // Recibir parámetros de la solicitud
        $periodo = $request->input('periodo');
        $dni = $request->input('personal');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        // Realizar la consulta con Common Table Expressions (CTEs) para obtener los días de vacaciones, licencias y permisos
        $query = DB::select("
            WITH PeriodosUnicos AS (
                SELECT DISTINCT periodo, personal_id
                FROM cronograma_vac
                -- Filtrar por periodo si se pasa como parámetro
                " . ($periodo ? "WHERE periodo = ?" : "") . "
            ),
            DiasVacaciones AS (
                SELECT personal_id, periodo, SUM(CASE WHEN suspencion = 'NO' THEN dias ELSE 0 END) AS dias_vacaciones
                FROM vacaciones
                GROUP BY personal_id, periodo
            ),
            DiasLicencias AS (
                SELECT personal_id, periodo, SUM(CASE WHEN acuentavac = 'SI' THEN dias ELSE 0 END) AS dias_licencias
                FROM licencias
                GROUP BY personal_id, periodo
            ),
            DiasPermisos AS (
                SELECT personal_id, periodo, SUM(CASE WHEN acuentavac = 'SI' THEN dias ELSE 0 END) AS dias_permisos
                FROM permisos
                GROUP BY personal_id, periodo
            ),
            UltimoVinculo AS (
                SELECT 
                    v.personal_id,
                    v.id_regimen,
                    v.id_condicion_laboral,
                    ROW_NUMBER() OVER (PARTITION BY v.personal_id ORDER BY v.fecha_ini DESC) AS rn
                FROM vinculos v
            )
            SELECT
                p.nro_documento_id AS dni,
                CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres) AS nombre,
                pu.periodo,
                r.nombre AS regimen,
                rm.nombre AS condicion,
                COALESCE(dv.dias_vacaciones, 0) AS dias_vacaciones,
                COALESCE(dl.dias_licencias, 0) AS dias_licencias,
                COALESCE(dp.dias_permisos, 0) AS dias_permisos,
                30 - (
                    COALESCE(dv.dias_vacaciones, 0) +
                    COALESCE(dl.dias_licencias, 0) +
                    COALESCE(dp.dias_permisos, 0)
                ) AS dias_adeudados
            FROM PeriodosUnicos pu
            LEFT JOIN DiasVacaciones dv ON pu.periodo = dv.periodo AND pu.personal_id = dv.personal_id
            LEFT JOIN DiasLicencias dl ON pu.periodo = dl.periodo AND pu.personal_id = dl.personal_id
            LEFT JOIN DiasPermisos dp ON pu.periodo = dp.periodo AND pu.personal_id = dp.personal_id
            LEFT JOIN personal p ON pu.personal_id = p.id_personal
            LEFT JOIN (
                SELECT personal_id, id_regimen, id_condicion_laboral
                FROM UltimoVinculo
                WHERE rn = 1
            ) uv ON uv.personal_id = p.id_personal
            LEFT JOIN regimen r ON uv.id_regimen = r.id
            LEFT JOIN condicion_laboral rm ON uv.id_condicion_laboral = rm.id
        ", [
            $periodo // Este es el parámetro que se pasa a la consulta si se especifica un periodo.
        ]);

        // Aplicar filtros adicionales dinámicamente
        if ($dni) {
            $query = array_filter($query, function($item) use ($dni) {
                return strpos($item->nombre, $dni) !== false; // Filtra por nombre si se pasa el DNI
            });
        }

        if ($regimen) {
            $query = array_filter($query, function($item) use ($regimen) {
                return $item->regimen === $regimen; // Filtra por régimen
            });
        }

        if ($condicion) {
            $query = array_filter($query, function($item) use ($condicion) {
                return $item->condicion === $condicion; // Filtra por condición laboral
            });
        }

        // Mapeo de encabezados
        $encabezados = [
            'dni' => 'DNI',
            'nombre' => 'Nombre Completo',
            'regimen' => 'Régimen',
            'condicion' => 'Condición Laboral',
            'periodo' => 'Periodo',
            'dias_vacaciones' => 'Días Vacaciones',
            'dias_licencias' => 'Días Licencias',
            'dias_permisos' => 'Días Permisos',
            'dias_adeudados' => 'Días Adeudados'
        ];

        // Devolver los resultados en formato collect
        return [
            'resultados' => collect($query),
            'encabezados' => $encabezados
        ];
    }


// Función auxiliar para obtener el nombre completo del personal por DNIP
private function getNombrePersonal($personal_id)
{
    return DB::table('personal')
        ->where('id_personal', $personal_id)
        ->select(DB::raw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre"))
        ->value('nombre');
}

    private function reporteVacaciones(Request $request)
    {
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $dni = $request->input('personal');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $fecha_inicio_desde = $request->input('fecha_inicio_desde');
        $fecha_inicio_hasta = $request->input('fecha_inicio_hasta');
    
        $query = DB::table('vacaciones as v')
            ->select(
                'p.id_personal',
                'regimen_vinculo.nombre as regimen',
                DB::raw("p.nro_documento_id as nro_dni"),
                DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres) as nombre"),
                'condicion_vinculo.nombre as condicion',
                'v.periodo',
                'v.dias',
                'v.fecha_ini',
                DB::raw("FORMAT(v.fecha_ini, 'dd-MM-yyyy') as fecha_ini"), 
                'v.fecha_fin',
                DB::raw("FORMAT(v.fecha_fin, 'dd-MM-yyyy') as fecha_fin"),
                'v.mes',
                'tipodoc.nombre as tipo_documento',
                'v.nrodoc as numero_documento'
            )
            ->join('personal as p', 'v.personal_id', '=', 'p.id_personal')
            ->leftJoinSub(
                DB::table('vinculos as vin')
                    ->select('vin.personal_id', 'vin.id_cargo', 'vin.id_regimen', 'vin.id_condicion_laboral')
                    ->whereIn('vin.id', function ($query) {
                        $query->select('id')->from('vinculos')->whereColumn('vinculos.personal_id', 'vin.personal_id')->orderBy('fecha_ini', 'desc')->limit(1);
                    }),
                'last_vinculo',
                'last_vinculo.personal_id',
                '=',
                'v.personal_id'
            )
            ->leftJoin('regimen as regimen_vinculo', 'last_vinculo.id_regimen', '=', 'regimen_vinculo.id')
            ->leftJoin('condicion_laboral as condicion_vinculo', 'last_vinculo.id_condicion_laboral', '=', 'condicion_vinculo.id')
            ->join('tipodoc', 'v.idtd', '=', 'tipodoc.id')
            ->where('v.suspencion', 'NO')
            ->when($periodo, function($query) use ($periodo) {
                return $query->where('v.periodo', $periodo);
            })
            ->when($mes, function($query) use ($mes) {
                return $query->where('v.mes', $mes);
            })
            ->when($dni, function($query) use ($dni) {
                return $query->where(DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres)"),$dni);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('regimen.nombre', $regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('condicion_laboral.nombre', 'LIKE', '%' . $condicion . '%');
            })
            ->when($fecha_inicio_desde, function($query) use ($fecha_inicio_desde) {
                return $query->where('v.fecha_ini', '>=', $fecha_inicio_desde);
            })
            ->when($fecha_inicio_hasta, function($query) use ($fecha_inicio_hasta) {
                return $query->where('v.fecha_ini', '<=', $fecha_inicio_hasta);
            })
            ->orderBy('v.fecha_ini', 'asc');
    
        $resultados = $query->distinct()->get();
    
        $encabezadosPersonalizados = [
            'nro_dni' => 'DNI',
            'nombre' => 'personal',
            'regimen' => 'REGIMEN',
            'condicion' => 'Condición',
            'periodo' => 'Período',
            'dias' => 'dias',
            'mes' => 'Mes',
            'fecha_ini' => 'Desde',
            'fecha_fin' => 'Hasta',
            'tipo_documento' => 'Tipo de Documento',
            'numero_documento' => 'N° de Documento'
        ];
    
        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }
    
    private function reporteLicencias(Request $request)
    {
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $dni = $request->input('personal');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $fecha_inicio_desde = $request->input('fecha_inicio_desde');
        $fecha_inicio_hasta = $request->input('fecha_inicio_hasta');
        
        $query = DB::table('licencias as l')
            ->select(
                'p.id_personal',
                'regimen_vinculo.nombre as regimen',
                DB::raw("p.nro_documento_id as nro_dni"),
                DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres) as nombre"),
                'condicion_vinculo.nombre as condicion',
                'l.descripcion',
                'l.periodo',
                'l.acuentavac',
                'l.congoce',
                'l.dias',
                'l.fecha_ini',
                'l.fecha_fin',
                DB::raw("FORMAT(l.fecha_ini, 'dd-MM-yyyy') as fecha_ini"),
                DB::raw("FORMAT(l.fecha_fin, 'dd-MM-yyyy') as fecha_fin"),
                'l.mes',
                'tipodoc.nombre as tipo_documento',
                'l.nrodoc as numero_documento',
            )
            ->join('personal as p', 'l.personal_id', '=', 'p.id_personal')
            ->leftJoinSub(
                DB::table('vinculos as vin')
                    ->select('vin.personal_id', 'vin.id_cargo', 'vin.id_regimen', 'vin.id_condicion_laboral')
                    ->whereIn('vin.id', function ($query) {
                        $query->select('id')->from('vinculos')->whereColumn('vinculos.personal_id', 'vin.personal_id')->orderBy('fecha_ini', 'desc')->limit(1);
                    }),
                'last_vinculo',
                'last_vinculo.personal_id',
                '=',
                'l.personal_id'
            )
            ->leftJoin('regimen as regimen_vinculo', 'last_vinculo.id_regimen', '=', 'regimen_vinculo.id')
            ->leftJoin('condicion_laboral as condicion_vinculo', 'last_vinculo.id_condicion_laboral', '=', 'condicion_vinculo.id')
            ->join('tipodoc', 'l.idtd', '=', 'tipodoc.id')
            ->when($periodo, function($query) use ($periodo) {
                return $query->where('l.periodo', $periodo);
            })
            ->when($mes, function($query) use ($mes) {
                return $query->where('l.mes', $mes);
            })
            ->when($dni, function($query) use ($dni) {
                return $query->where(DB::raw("CONCAT(p.Apaterno, ' ', p.Amaterno, ' ', p.Nombres)"),$dni);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('regimen.nombre', $regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('condicion_laboral.nombre',$condicion);
            })
            ->when($fecha_inicio_desde, function($query) use ($fecha_inicio_desde) {
                return $query->where('l.fecha_ini', '>=', $fecha_inicio_desde);
            })
            ->when($fecha_inicio_hasta, function($query) use ($fecha_inicio_hasta) {
                return $query->where('l.fecha_ini', '<=', $fecha_inicio_hasta);
            })

            ->orderBy('l.fecha_ini', 'asc');
    
        $resultados = $query->distinct()->get();
    
        $encabezadosPersonalizados = [
            'nro_dni' => 'DNI',
            'nombre' => 'personal',
            'regimen' => 'REGIMEN',
            'condicion' => 'Condición',
            'acuentavac' => 'A CUENTA VACACIONES',
            'dias' => 'dias',
            'congoce' => 'CON GOCE DE HABER',
            'periodo' => 'Período',
            'mes' => 'Mes',
            'fecha_ini' => 'Desde',
            'fecha_fin' => 'Hasta',
            'tipo_documento' => 'Tipo de Documento',
            'descripcion' => 'Descripcion',
            'numero_documento' => 'N° de Documento'
        ];
    
        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }
    
    private function reportePermisos(Request $request)
    {
        $periodo = $request->input('periodo');
        $mes = $request->input('mes');
        $dni = $request->input('personal');
        $regimen = $request->input('regimen');
        $condicion = $request->input('condicion');
        $fecha_inicio_desde = $request->input('fecha_inicio_desde');
        $fecha_inicio_hasta = $request->input('fecha_inicio_hasta');
        
        $query = DB::table('permisos as p')
            ->select(
                'per.id_personal',
                'regimen_vinculo.nombre as regimen',
                DB::raw("per.nro_documento_id as nro_dni"),
                DB::raw("CONCAT(per.Apaterno, ' ', per.Amaterno, ' ', per.Nombres) as nombre"),
                'condicion_vinculo.nombre as condicion',
                'p.descripcion',
                'p.periodo',
                'p.acuentavac',
                'p.dias',
                'p.fecha_ini',
                'p.fecha_fin',
                DB::raw("FORMAT(p.fecha_ini, 'dd-MM-yyyy') as fecha_ini"),
                DB::raw("FORMAT(p.fecha_fin, 'dd-MM-yyyy') as fecha_fin"),
                'p.mes',
                'tipodoc.nombre as tipo_documento',
                'p.nrodoc as numero_documento'
            )
            ->join('personal as per', 'p.personal_id', '=', 'per.id_personal')
            ->leftJoinSub(
                DB::table('vinculos as vin')
                    ->select('vin.personal_id', 'vin.id_cargo', 'vin.id_regimen', 'vin.id_condicion_laboral')
                    ->whereIn('vin.id', function ($query) {
                        $query->select('id')->from('vinculos')->whereColumn('vinculos.personal_id', 'vin.personal_id')->orderBy('fecha_ini', 'desc')->limit(1);
                    }),
                'last_vinculo',
                'last_vinculo.personal_id',
                '=',
                'p.personal_id'
            )
            ->leftJoin('regimen as regimen_vinculo', 'last_vinculo.id_regimen', '=', 'regimen_vinculo.id')
            ->leftJoin('condicion_laboral as condicion_vinculo', 'last_vinculo.id_condicion_laboral', '=', 'condicion_vinculo.id')
            ->join('tipodoc', 'p.idtd', '=', 'tipodoc.id')
            ->when($periodo, function($query) use ($periodo) {
                return $query->where('p.periodo', $periodo);
            })
            ->when($mes, function($query) use ($mes) {
                return $query->where('p.mes', $mes);
            })
            ->when($dni, function($query) use ($dni) {
                return $query->where(DB::raw("CONCAT(per.Apaterno, ' ', per.Amaterno, ' ', per.Nombres)"),$dni);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('regimen.nombre', $regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('condicion_laboral.nombre',$condicion);
            })
            ->when($fecha_inicio_desde, function($query) use ($fecha_inicio_desde) {
                return $query->where('p.fecha_ini', '>=', $fecha_inicio_desde);
            })
            ->when($fecha_inicio_hasta, function($query) use ($fecha_inicio_hasta) {
                return $query->where('p.fecha_ini', '<=', $fecha_inicio_hasta);
            })
            ->orderBy('p.fecha_ini', 'asc');

        $resultados = $query->distinct()->get();

        $encabezadosPersonalizados = [
            'nro_dni' => 'DNI',
            'nombre' => 'personal',
            'regimen' => 'REGIMEN',
            'condicion' => 'Condición',
            'descripcion' => 'descripcion',
            'acuentavac' => 'A CUENTA VACACIONES',
            'dias' => 'dias',
            'periodo' => 'Período',
            'mes' => 'Mes',
            'fecha_ini' => 'Desde',
            'fecha_fin' => 'Hasta',
            'tipo_documento' => 'Tipo de Documento',
            'numero_documento' => 'N° de Documento'
        ];

        return ['resultados' => $resultados, 'encabezados' => $encabezadosPersonalizados];
    }

    
}


