<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vinculo;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use DB;

class VinculoController extends Controller
{

    public function index(Request $request)
    {
        $columns = Schema::getColumnListing('vinculos');
        $columns = array_diff($columns, ['created_at', 'updated_at', 'obras_pro']); // Excluir las columnas no necesarias.

        $prefixedColumns = array_map(function ($column) {
            return "vinculos.$column";
        }, $columns);

        //EVALUAR
        // Construir la consulta base con las columnas seleccionadas dinámicamente.
        $reg = DB::table('vinculos')
            ->select(array_merge(
                $prefixedColumns,
                [
                    'regimen.id as regimen',
                    'regimen.nombre as id_regimen',
                    'vinculos.archivo as archivo',
                    'tipodoc.nombre as td',
                    'tdf.nombre as id_tipo_documento_fin',
                    'area.nombre as unidad_organica',
                    'area.id as id_unidad_organica',
                    'archivo.nro_folio',
                    'condicion_laboral.nombre as id_condicion_laboral',
                    'cargo.id as id_cargo',
                    'cargo.nombre as cargo',
                    'depen.id as id_depens',
                    'depen.nombre as depens',
                    DB::raw('(SELECT COUNT(*) FROM adendas WHERE adendas.id_vinculo = vinculos.id) as cantidad_adendas'),
                    DB::raw('
                        COALESCE(
                            (SELECT TOP 1 fecha_fin FROM adendas WHERE adendas.id_vinculo = vinculos.id ORDER BY id DESC),
                            vinculos.fecha_fin
                        ) as fecha_fin'),
                    DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo'),
                ]
            ))
            ->leftJoin('tipodoc', 'vinculos.id_tipo_documento', '=', 'tipodoc.id')
            ->leftJoin('tipodoc as tdf', 'vinculos.id_tipo_documento_fin', '=', 'tdf.id')
            ->leftJoin('regimen', 'vinculos.id_regimen', '=', 'regimen.id')
            ->leftJoin('archivo', 'vinculos.archivo', '=', 'archivo.id')
            ->leftJoin('area', 'vinculos.id_unidad_organica', '=', 'area.id')
            ->leftJoin('area as depen', 'vinculos.id_depens', '=', 'depen.id')
            ->leftJoin('cargo', 'vinculos.id_cargo', '=', 'cargo.id')
            ->leftJoin('personal', 'vinculos.personal_id', '=', 'personal.id_personal')
            ->leftJoin('condicion_laboral', 'vinculos.id_condicion_laboral', '=', 'condicion_laboral.id')
            ->groupBy(
                array_merge($prefixedColumns, [
                    'regimen.id',
                    'regimen.nombre',
                    'condicion_laboral.nombre',
                    'tipodoc.nombre',
                    'tdf.nombre',
                    'archivo.nro_folio',
                    'area.nombre',
                    'area.id',
                    'cargo.nombre',
                    'cargo.id',
                    'depen.nombre',
                    'depen.id',
                    'personal.Apaterno',
                    'personal.Amaterno',
                    'personal.Nombres',
                ])
            );
        if ($request->has('filter_field') && $request->has('filter_value')) {
            $filterField = $request->input('filter_field');
            $filterValue = $request->input('filter_value');
            if (!empty($filterValue) && in_array($filterField, $columns)) {
                $reg->where("vinculos.$filterField", $filterValue);
            }
        }
        $reg = $reg->get();
        $reg->transform(function ($item) {
            if (!empty($item->obras_pro)) {
                $item->id_unidad_organica = $item->id_unidad_organica . " : " . implode(", ", json_decode($item->obras_pro));
            }
            return $item;
        });
        return Datatables::of($reg)->make(true);
    }

    public function show($id)
    {
        $c = Vinculo::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    public static function store(Request $request)
    {
        $data = $request->except('archivo', 'obras_pro');
        $request->validate([
            'personal_id' => 'required',
        ]);

        $personalId = $request->input('personal_id');
        $nuevaFechaIni = Carbon::parse($request->input('fecha_ini'));

        // Buscar el último vínculo del personal por fecha_ini descendente
        $ultimoVinculo = DB::table('vinculos')
            ->where('personal_id', $personalId)
            ->orderBy('fecha_ini', 'desc')
            ->first();

        if ($ultimoVinculo) {
            $ultimaFechaIni = Carbon::parse($ultimoVinculo->fecha_ini);

            // Verificar condiciones: nueva fecha_ini > última fecha_ini y fecha_fin no es null
            if ($nuevaFechaIni->gt($ultimaFechaIni) && !is_null($ultimoVinculo->fecha_fin)) {

                // Verificar el tipo de personal actual
                $tipoPersonal = DB::table('personal')
                    ->where('id_personal', $personalId)
                    ->value('id_tipo_personal');
                // Si el tipo de personal es 2 (sin vínculo), actualizarlo a 1 (activo)

                if ($tipoPersonal === "2") {

                    DB::table('personal')
                        ->where('id_personal', $personalId)
                        ->update(['id_tipo_personal' => 1]);
                }
            }
        }
        // Check if obras_pro is set and not empty
        if (!empty($request->input('obras_pro'))) {
            $data['obras_pro'] = $request->input('obras_pro');
        }
        $archivo = FileHelper::createArchivo2($request->file('archivo'), $request->nro_folios ?? null, $request->personal_id, "02");
        if ($archivo) {
            $data['archivo'] = $archivo->id;
            $archivo2 = FileHelper::createArchivo2($request->file('archivo_cese'), $request->nro_folios2 ?? null, $request->personal_id, "02");
            if ($archivo2) {
                $data['archivo_cese'] = $archivo2->id;
            }
        }
        $s = Vinculo::create($data);

    }

    public static function update(Request $request, $id)
    {
        $data = $request->except('archivo', 'obras_pro');
        $cese = $request->input('id_motivo_fin_vinculo');

        if (!empty($request->input('obras_pro'))) {
            $data['obras_pro'] = $request->input('obras_pro');
        }

        $contrato = Vinculo::findOrFail($id);
        //EVALUAR
        if ($cese !== null) {
            // Buscar el ID del régimen modalidad donde el nombre sea 'cesante'
            // Tipo Personal
            // 1 activo
            // 2 sin vinculo
            // 3 Pensionista
            $updated = DB::table('personal')
                ->where('id_personal', $contrato->personal_id)
                ->update(['id_tipo_personal' => 2]);

        }
        $archivo = FileHelper::updateArchivo2($request->file('archivo'), $contrato->personal_id, "02", $contrato->archivo, $request->nro_folios ?? null);
        if ($archivo !== null) {
            $data['archivo'] = $archivo->id;
        }
        $archivo2 = FileHelper::updateArchivo2($request->file('archivo_cese'), $contrato->personal_id, "12", $contrato->archivo_cese, $request->nro_folios2 ?? null);
        if ($archivo2 !== null) {
            $data['archivo_cese'] = $archivo2->id;
        }
        $contrato->update($data);
        return response()->json($contrato->toArray());
    }


    public function destroy($id)
    {
        $t = Vinculo::find($id);
        if ($t) {
            if ($t->archivo) {
                Archivo::where('id', $t->archivo)->delete();
            }
            if ($t->archivo_cese) {
                Archivo::where('id', $t->archivo_cese)->delete();
            }
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public function get_form_contrato()
    {
        return view('datoslegajo.forms.vinculos.formContrato');
    }
    public function get_form_nombramiento()
    {
        return view('datoslegajo.forms.vinculos.formNombramiento');
    }

    public function datosVinculo(Request $request)
    {
        $id = $request->query('id');

        $vinculo = DB::select('exec datosVinculo ?', [$request->id]);
        $rotaciones=null;
        $rotaciones = DB::select('exec ultimasRotaciones ?', [$id]);
        $encargaturas=null;
        $encargaturas = DB::select('exec ultimaEncargatura ?', [$id]);
        $vacaciones=null;
        $vacaciones = DB::select('exec historialVacaciones ?, ?', [$id, date("Y")]);
        $licencias=null;
        $licencias = DB::select('exec verLicencias ?', [$id]);
        $permisos=null;
        $permisos = DB::select('exec verPermisos ?', [$id]);
        $compensaciones=null;
        $compensaciones = DB::select('exec verCompensaciones ?', [$id]);
        $sanciones=null;
        $sanciones = DB::select('exec verSanciones ?', [$id]);
        $reconocimientos=null;
        $reconocimientos = DB::select('exec verReconocimientos ?', [$id]);
        return response()->json([
            "vinculo"=>$vinculo, 
            'rotaciones'=>$rotaciones,
            'encargaturas'=>$encargaturas,
            'vacaciones'=>$vacaciones,
            'licencias'=>$licencias,
            'permisos'=>$permisos,
            'compensaciones'=>$compensaciones,
            'sanciones'=>$sanciones,
            'reconocimientos'=>$reconocimientos,
            ]);
    }
}

