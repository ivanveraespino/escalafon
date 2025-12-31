<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Personal;
use Illuminate\Support\Facades\Schema;

use DB;

class VLPController extends Controller
{

public function getMovimientos(Request $request) {
    $periodo = $request->periodo;
    $repo = $request->repo;
    $regimen = $request->regimen; // Parámetro para filtrar por régimen
    $bindings = [$periodo, $periodo, $periodo, $periodo, $periodo];
    
    // Condición para filtrar por repositorio
    $whereCondition = $repo ? "cv.idvo = ?" : "cv.idvr IS NULL";
    if ($repo) {
        $bindings[] = $repo;
    }
    
    // Condición adicional para filtrar por régimen en la tabla personal
    if ($regimen) {
        $whereCondition .= " AND p.id_regimen = ?";
        $bindings[] = $regimen;
    }
    
    $data = DB::select("
        WITH DiasVacaciones AS (
            SELECT idvinculo, SUM(CASE WHEN suspension = 'NO' THEN dias ELSE 0 END) AS dias_vacaciones
            FROM vacaciones WHERE periodo = ? GROUP BY idvinculo
        ),
        DiasLicencias AS (
            SELECT idvinculo, SUM(CASE WHEN acuentavac = 'SI' THEN dias ELSE 0 END) AS dias_licencias
            FROM licencias WHERE periodo = ? GROUP BY idvinculo
        ),
        DiasPermisos AS (
            SELECT idvinculo, SUM(CASE WHEN acuentavac = 'SI' THEN dias ELSE 0 END) AS dias_permisos
            FROM permisos WHERE periodo = ? GROUP BY idvinculo
        ),
        UltimosVinculos AS (
                SELECT 
                    id,
                    personal_id, 
                    id_regimen, 
                    id_condicion_laboral, 
                    fecha_ini,
                    ROW_NUMBER() OVER (PARTITION BY personal_id ORDER BY fecha_ini DESC) AS row_num
                FROM vinculos
            ) 
        SELECT
            p.id_personal,
            p.Apaterno + ' ' + p.Amaterno + ' ' + p.Nombres AS personal,
            CONCAT(regimen.nombre, ' ', condicion_laboral.nombre) AS regimen_condicion, 
            p.nro_documento_id AS nro_doc,
            COALESCE(dv.dias_vacaciones, 0) AS dias_vacaciones,
            COALESCE(dl.dias_licencias, 0) AS dias_licencias,
            COALESCE(dp.dias_permisos, 0) AS dias_permisos,
            CASE WHEN cv.id IS NOT NULL THEN
                30 - COALESCE(dv.dias_vacaciones, 0) - COALESCE(dl.dias_licencias, 0) - COALESCE(dp.dias_permisos, 0)
            ELSE
                0 - COALESCE(dv.dias_vacaciones, 0) - COALESCE(dl.dias_licencias, 0) - COALESCE(dp.dias_permisos, 0)
            END AS dias_restantes,
            cv.mes,
            cv.fecha_ini,
            cv.fecha_fin,
            td.nombre + ' N° ' + cv.nrodoc AS doc,
            CASE
                WHEN cv.personal_id IS NULL THEN 'No'
                WHEN cv.idvo IS NULL OR cv.idvo = cv.id THEN 'Sí'
                ELSE 'r'
            END AS estado,
            cv.archivo,
            cv.observaciones AS obs,
            cv.idvr,
            cv.id AS idcv,
            (SELECT COUNT(*) FROM cronograma_vac cv2 WHERE cv2.idvo = cv.idvo) AS total,
            CASE
                WHEN cv.idvo IS NULL THEN cv.id
                ELSE cv.idvo
            END AS idvo,
            (SELECT cv1.mes FROM cronograma_vac cv1 WHERE cv1.id = cv.idvo) AS desde,
            cv.mes AS hasta,
            ? AS periodo_programacion
        FROM personal p
        LEFT JOIN UltimosVinculos AS vin ON p.id_personal = vin.personal_id AND vin.row_num = 1
        LEFT JOIN DiasVacaciones dv ON vin.id = dv.idvinculo
        LEFT JOIN DiasLicencias dl ON vin.id = dl.idvinculo
        LEFT JOIN DiasPermisos dp ON vin.id = dp.idvinculo
        
        LEFT JOIN regimen ON vin.id_regimen = regimen.id
        LEFT JOIN condicion_laboral ON vin.id_condicion_laboral = condicion_laboral.id
        LEFT JOIN cronograma_vac cv ON p.id_personal = cv.personal_id AND cv.periodo = ?
        LEFT JOIN tipodoc td ON cv.idtd = td.id
        WHERE $whereCondition
    ", $bindings);
    
    foreach ($data as $row) {
        // Validar que los meses no sean nulos o vacíos
        if (!empty($row->mes)) {
            $meses = json_decode($row->mes, true);
            if (is_array($meses)) {
                $row->mes = implode("\n", $meses); // Convertir array de meses a cadena con saltos de línea
            }
        }
    
        // Convertir desde y hasta con saltos de línea
        if (!empty($row->desde)) {
            $desde = json_decode($row->desde, true);
            if (is_array($desde)) {
                $row->desde = implode("\n", $desde);
            }
        }
    
        if (!empty($row->hasta)) {
            $hasta = json_decode($row->hasta, true);
            if (is_array($hasta)) {
                $row->hasta = implode("\n", $hasta);
            }
        }
    
        // Calcular días entre fecha_ini y fecha_fin
        if (!empty($row->fecha_ini) && !empty($row->fecha_fin)) {
            $fechas_ini = json_decode($row->fecha_ini, true);
            $fechas_fin = json_decode($row->fecha_fin, true);
            if (is_array($fechas_ini) && is_array($fechas_fin)) {
                $dias = [];
                foreach ($fechas_ini as $index => $fecha_ini) {
                    if (!empty($fecha_ini) && !empty($fechas_fin[$index])) {
                        $fecha_inicio = new \DateTime($fecha_ini);
                        $fecha_final = new \DateTime($fechas_fin[$index]);
                        $interval = $fecha_inicio->diff($fecha_final);
                        $dias[] = $interval->days + 1; // Calcular los días de diferencia y sumar un día
                    }
                }
                $row->dias = implode("\n", $dias); // Utiliza 
            } else {
                if (!empty($row->fecha_ini) && !empty($row->fecha_fin)) {
                    $fecha_inicio = new \DateTime($row->fecha_ini);
                    $fecha_final = new \DateTime($row->fecha_fin);
                    $interval = $fecha_inicio->diff($fecha_final);
                    $row->dias = $interval->days + 1; // Calcular los días de diferencia
                } else {
                    $row->dias = '';
                }
            }
        } else {
            $row->dias = '';
        }
        // Convertir las fechas de inicio y fin a formato d-m-Y y unir con saltos de línea
        if (!empty($row->fecha_ini)) {
            $fechas_ini = json_decode($row->fecha_ini, true);
            $row->fecha_ini = is_array($fechas_ini) ? implode("\n", array_filter(array_map(function($fecha) {
                return $fecha ? date('d-m-Y', strtotime($fecha)) : null;
            }, $fechas_ini))) : date('d-m-Y', strtotime($row->fecha_ini));
        } else {
            $row->fecha_ini = '';
        }
    
        if (!empty($row->fecha_fin)) {
            $fechas_fin = json_decode($row->fecha_fin, true);
            $row->fecha_fin = is_array($fechas_fin) ? implode("\n", array_filter(array_map(function($fecha) {
                return $fecha ? date('d-m-Y', strtotime($fecha)) : null;
            }, $fechas_fin))) : date('d-m-Y', strtotime($row->fecha_fin));
        } else {
            $row->fecha_fin = '';
        }
    }
    return Datatables::of($data)->make(true);
}
    
    public function buscarUsuarios(Request $request)
    {
        $query = $request->get('query');
        $driver = DB::getDriverName();
        $usuarios = Personal::select(
            'personal.*',
        )
        ->where(DB::raw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres, ' ', nro_documento_id)"), 'LIKE', "%{$query}%")
        ->get();

        $resultados = $usuarios->map(function ($usuario) {
            return [
                'personal_id' => $usuario->id_personal,
                'nombre_completo' => "{$usuario->Apaterno} {$usuario->Amaterno} {$usuario->Nombres}",
            ];
        });

        return response()->json($resultados); // Devuelve los resultados en JSON
    }

    
    public function buscarCronograma(Request $request)
    {
        $personal_id = $request->personal_id;
        $periodo = $request->periodo;

        $dias_cronograma = DB::table('cronograma_vac')
        ->where('personal_id', $personal_id)
        ->where('periodo', $periodo)
        ->whereNull('idvr') 
        ->pluck('dias'); 
        $dias_cv = 0;
        foreach ($dias_cronograma as $dias_string) {
            $dias_array = json_decode($dias_string, true);
            if (is_array($dias_array)) {
                $dias_cv += array_sum($dias_array);
            }
        } 


        $dias_usados_cv = 0;
        $dias_usados = 0;
        $dias_v = 0;
        $dias_l = 0;
        $dias_p = 0;
        $s_dias_adelantado = 0;
        $dias_ad_v = 0;
        $dias_ad_l = 0;
        $dias_ad_p = 0;

        if ($periodo > 2021) { 
      
            $vacas = DB::table('vacaciones as v')
                ->selectRaw('
                    SUM(dias) as dias_v, 
                    SUM(CASE WHEN adelantado = 1 THEN dias ELSE 0 END) as dias_adelantado
                ')
                ->join('vinculos as vin','vin.id','=','v.idvinculo')
                ->where('vin.personal_id', $personal_id)
                ->where('v.periodo', $periodo)
                ->where('v.suspension', 'NO')
                ->first();

            $dias_v = $vacas->dias_v ?? 0; // Total de días
            $dias_ad_v = $vacas->dias_adelantado ?? 0; 

            $lic = DB::table('licencias as l')
                ->join('vinculos as vin','vin.id','=','l.idvinculo')
                ->where('vin.personal_id', $personal_id)
                ->where('l.periodo', $periodo)
                ->where('l.acuentavac', 'SI')
                ->selectRaw('
                    SUM(l.dias+l.mes*30+l.anio*365) as dias_l, 
                    SUM(CASE WHEN l.adelantado = 1 THEN l.dias ELSE 0 END) as dias_adelantado
                ')->first();
            $dias_l = $lic->dias_l ?? 0; // Total de día
            $dias_ad_l = $lic->dias_adelantado ?? 0; // Si existe al menos un adelantado = 1 


            $per = DB::table('permisos as p')
            ->join('vinculos as vin','vin.id','=','p.idvinculo')
                ->where('vin.personal_id', $personal_id)
                ->where('p.periodo', $periodo)
                ->where('p.acuentavac', 'SI')
                ->selectRaw('
                    SUM(p.dias+p.mes*30+p.anio*365) as dias_p, 
                    SUM(CASE WHEN p.adelantado = 1 THEN p.dias ELSE 0 END) as dias_adelantado
                ')->first();
            $dias_p = $per->dias_p ?? 0; // Total de día
            $dias_ad_p = $per->dias_adelantado ?? 0;

            $dias_usados_cv = $dias_cv + $dias_v + $dias_l + $dias_p;
            $dias_usados = $dias_v + $dias_l + $dias_p;
            $s_dias_adelantado = $dias_ad_v + $dias_ad_l + $dias_ad_p;
        } 

        return response()->json([
            'hasCronograma' => $dias_cv,
            'dias_usados_cv' => $dias_usados,
            'dias_usados' => $dias_usados,
            'dias_cv' => $dias_cv,
            'dias_v' => $dias_v,
            'dias_l' => $dias_l,
            'dias_p' => $dias_p,
            'dias_ad_v' => $dias_ad_v,
            'dias_ad_l' => $dias_ad_l,
            'dias_ad_p' => $dias_ad_p,
            's_dias_adelantado' => $s_dias_adelantado,
        ]);
    }

    public function index()
    {
        
        $tdoc = DB::table('tipodoc')->get();
        $reg = DB::table('regimen')->get();
        return view('VLP.index',compact('tdoc','reg'));
    }
    public function store()
    {
        
    }

}
