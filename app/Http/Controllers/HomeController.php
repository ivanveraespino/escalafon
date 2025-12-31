<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Personal;
use App\Models\CronogramaVacaciones;
use App\Models\CondicionLaboral;


use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use DB;

class HomeController extends Controller
{
    
    public function index(): View
    {
        $regimenes = CondicionLaboral::all();
        $regimenData = [
            'labels' => [],
            'datasets' => [
                [
                    'data' => [],
                    'backgroundColor' => []
                ]
            ]
        ];
        

        $colors = ['#4CAF50', '#F44336', '#FFCE56', '#36A2EB', '#FF6384']; // Colores para los gráficos
        $totalActivos = 0;
        $totalInactivos = 0;

        foreach ($regimenes as $key => $regimen) {
        $subquery = "
                WITH UltimosVinculos AS (
                        SELECT 
                    v.personal_id, 
                    v.id_condicion_laboral, 
                    v.fecha_ini
                FROM vinculos v
                WHERE v.fecha_ini = (
                    SELECT MAX(fecha_ini) 
                    FROM vinculos 
                    WHERE personal_id = v.personal_id
                    )
                )
                SELECT COUNT(*) as total
                FROM UltimosVinculos
                WHERE id_condicion_laboral = ?;
                ";
            $activos = DB::select($subquery, [$regimen->id])[0]->total;
            $inactivos = DB::select($subquery, [$regimen->id])[0]->total;
            $regimenData['labels'][] = $regimen->nombre;
            $regimenData['datasets'][0]['data'][] = $activos;
            $regimenData['datasets'][0]['backgroundColor'][] = $colors[$key % count($colors)];
            $totalActivos += $activos;
            $totalInactivos += $inactivos;
        }
        
        //RUTAS_CHECK
        return view('home', [
            'totalPersonal' => Personal::count(),
            'regimenData' => json_encode($regimenData),
            'activoInactivoData' => json_encode([
                'labels' => ['Activo', 'Inactivo'],
                'datasets' => [
                    [
                        'data' => [$totalActivos, $totalInactivos],
                        'backgroundColor' => ['#4CAF50', '#F44336']
                    ]
                ]
            ])
        ]);
    }

    public function mostrara_vac_rec(Request $request)
    {
        $cronogramas = DB::table('cronograma_vac')
            ->join('personal', 'cronograma_vac.personal_id', '=', 'personal.id_personal')
        
            ->select(
                'cronograma_vac.*',
                DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo'),
            )
            ->whereNull('cronograma_vac.idvr')
            ->get();
    
        // Filtrando y ordenando los datos en PHP
        $filteredCronogramas = $cronogramas->filter(function ($cronograma) {
            $fecha_ini = json_decode($cronograma->fecha_ini, true);
            return isset($fecha_ini[0]) && Carbon::parse($fecha_ini[0])->gte(Carbon::now()->startOfDay());
        });
    
        $sortedCronogramas = $filteredCronogramas->sortBy(function ($cronograma) {
            $fecha_ini = json_decode($cronograma->fecha_ini, true);
            return isset($fecha_ini[0]) ? Carbon::parse($fecha_ini[0]) : Carbon::now()->startOfDay();
        });
    
        // Para mostrar los datos en Datatables
        return Datatables::of($sortedCronogramas)
            ->addColumn('fecha_inicio', function ($cronograma) {
                $fecha_ini = json_decode($cronograma->fecha_ini, true);
                return isset($fecha_ini[0]) ? Carbon::parse($fecha_ini[0])->format('d/m/Y') : null;
            })
            ->make(true);
    }
    
    public function getRepoTiempo(Request $request)
    {
        $fecha = $request->fecha;
        $driver = DB::getDriverName();
    
        // Subquery para el contrato más antiguo
        $oldestContract = DB::table('vinculos')
            ->select('personal_id', DB::raw('MIN(fecha_ini) as inicio_vinculo'))
            ->groupBy('personal_id');
    
        // Subquery para el contrato más reciente
        $recentContract = DB::table('vinculos as c1')
            ->select('c1.personal_id', 'c1.id_unidad_organica')
            ->whereColumn('c1.fecha_ini', '=', DB::raw('(SELECT MAX(fecha_ini) FROM vinculos WHERE vinculos.personal_id = c1.personal_id)'))
            ->whereColumn('c1.id', '=', $driver === 'sqlsrv' 
                ? DB::raw('(SELECT TOP 1 id FROM vinculos WHERE vinculos.personal_id = c1.personal_id ORDER BY fecha_ini DESC)')
                : DB::raw('(SELECT id FROM vinculos WHERE vinculos.personal_id = c1.personal_id ORDER BY fecha_ini DESC LIMIT 1)')
            )
            ->groupBy('c1.personal_id', 'c1.id_unidad_organica');
        // Construcción de la consulta principal
        //EVALUAR
        $data = DB::table('personal')
            ->leftJoinSub($oldestContract, 'oc', function ($join) {
                $join->on('personal.id_personal', '=', 'oc.personal_id');
            })
            ->leftJoinSub($recentContract, 'rc', function ($join) {
                $join->on('personal.id_personal', '=', 'rc.personal_id');
            })
            ->select(
                'personal.nro_documento_id',
                DB::raw("CONCAT(personal.Apaterno, ' ', personal.Amaterno, ' ', personal.Nombres) as personal"),
                'personal.FechaNacimiento',
                'rc.id_unidad_organica as cargo',
                'oc.inicio_vinculo'
            )->get();
    
        // Manipulación de datos en PHP
        $data = $data->map(function ($item) {
            $item->fecha_25_vinculo = Carbon::parse($item->inicio_vinculo)->addYears(25)->format('Y-m-d');
            $item->fecha_30_vinculo = Carbon::parse($item->inicio_vinculo)->addYears(30)->format('Y-m-d');
            $item->fecha_70 = Carbon::parse($item->FechaNacimiento)->addYears(70)->format('Y-m-d');
            return $item;
        });
    
        // Filtrar los datos basados en la fecha
        if ($fecha == 25) {
            $data = $data->filter(function ($item) {
                return Carbon::parse($item->fecha_25_vinculo)->year == Carbon::now()->year &&
                       Carbon::parse($item->fecha_25_vinculo)->month == Carbon::now()->month;
            });
        } elseif ($fecha == 30) {
            $data = $data->filter(function ($item) {
                return Carbon::parse($item->fecha_30_vinculo)->year == Carbon::now()->year &&
                       Carbon::parse($item->fecha_30_vinculo)->month == Carbon::now()->month;
            });
        } else {
            $data = $data->filter(function ($item) {
                return Carbon::parse($item->fecha_70)->year == Carbon::now()->year &&
                       Carbon::parse($item->fecha_70)->month == Carbon::now()->month;
            });
        }
    
        return Datatables::of($data)->make(true);
    }

    public function getVeinticincos(){
        $personal = DB::select('exec datosInicio ?', [25]);
        $html = view('Home.cesantes', ['datos'=> $personal] )->render();
    
        return response()->json(['html' => $html]);
    }

    public function getTreintas(){
        $personal = DB::select('exec datosInicio ?', [30]);
        $html = view('Home.cesantes', ['datos'=> $personal] )->render();
    
        return response()->json(['html' => $html]);
    }

    public function getCesantes(){
        $cesantes = DB::select('exec datosCesantes ?', [70]);
        $html = view('Home.cesantes', ['datos'=> $cesantes] )->render();
        return response()->json(['html' => $html]);
    }
}
