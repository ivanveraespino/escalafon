<?php

namespace App\Http\Controllers;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Regimen;
use App\Models\Personal;
use DB;

class ReporteTiempoController extends Controller
{
    public function index()
    {
        return view('Reporte_tiempo.index');
    }

    public function getRepoTiempo()
    {
        $oldestContract = DB::table('vinculos')
            ->select('personal_id', DB::raw('MIN(fecha_ini) as inicio_vinculo'))
            ->groupBy('personal_id');

   
        $recentContract = DB::table('vinculos as c1')
            ->select(
                'c1.personal_id', 
                'c1.id_unidad_organica', 
                'r.nombre as regimen', 
                'cl.nombre as condicion_laboral'
            )
            ->leftjoin('regimen as r', 'c1.id_regimen', '=', 'r.id') 
            ->leftjoin('condicion_laboral as cl', 'c1.id_condicion_laboral', '=', 'cl.id') 
            ->whereRaw('c1.fecha_ini = (SELECT MAX(fecha_ini) FROM vinculos WHERE vinculos.personal_id = c1.personal_id)')
            ->whereRaw('c1.id = (SELECT TOP 1 id FROM vinculos WHERE vinculos.personal_id = c1.personal_id ORDER BY fecha_ini DESC)')
            ->groupBy('c1.personal_id', 'c1.id_unidad_organica', 'r.nombre', 'cl.nombre');
        
        $data = DB::table('personal')
            ->leftJoinSub($oldestContract, 'oc', function ($join) {
                $join->on('personal.id_personal', '=', 'oc.personal_id');
            })
            ->leftJoinSub($recentContract, 'rc', function ($join) {
                $join->on('personal.id_personal', '=', 'rc.personal_id');
            })
            ->select(
                DB::raw("rc.regimen + ' ' + rc.condicion_laboral as regimen"),
                'personal.nro_documento_id',
                'personal.Apaterno',
                'personal.Amaterno',
                'personal.Nombres',
                'personal.FechaNacimiento',
                'rc.id_unidad_organica as cargo',
                'oc.inicio_vinculo',
                DB::raw("DATEADD(year, 25, oc.inicio_vinculo) as fecha_25_vinculo"),
                DB::raw("DATEADD(year, 30, oc.inicio_vinculo) as fecha_30_vinculo"),
                DB::raw("DATEADD(year, 70, personal.FechaNacimiento) as fecha_70")
            )
            ->get();

        return Datatables::of($data)->make(true);
    }
}
