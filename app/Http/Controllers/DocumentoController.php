<?php

namespace App\Http\Controllers;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Regimen;
use App\Models\Personal;
use App\Models\Archivo;
use App\Models\Tipodoc;

use DB;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $dcat = DB::table('categorias')->get();
        return view('Documentos.index', ['dcat' => $dcat]);
    }

    

    public function datoscampo($tipo = null) {
        $data = DB::table('tipodoc')
            ->select('tipodoc.*', 
            )
            ->get()
            ->map(function ($item) {
                $categoriaIds = json_decode($item->categoria, true);
                if (!empty($categoriaIds)) {
                    $nombresCategorias = DB::table('categorias')
                        ->whereIn('clave', $categoriaIds)
                        ->pluck('nombre')
                        ->toArray();
                    $item->categoria = implode(', ', $nombresCategorias);
                } else {
                    $item->categoria = '';
                }
                return $item;
            });
        
        return Datatables::of($data)->make(true);
    }


    public function mostrar($id)
    {
        $c = DB::table('tipodoc')->where('id', $id)->first();
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Contrato no encontrado'], 404);
        }
    }
    

    public static function guardar(Request $request)
    {
        $data = $request->except(["categoria"]);
        $categorias = $request->input('categoria');
        $categoriasJson = json_encode($categorias);
        $data['categoria'] = $categoriasJson;
        Tipodoc::create($data);
        return response()->json(['success' => 'Campo agregado!']);
    }
    
    
    public function edit($id, Request $request)
    {
        $data = $request->except(["_token"]);
        
        // Verificar si 'categoria' es un array y convertirlo a JSON
        if (isset($data['categoria']) && is_array($data['categoria'])) {
            $data['categoria'] = json_encode($data['categoria']);
        }
    
        if (DB::table('tipodoc')->where('id', $id)->update($data)) {
            return response()->json(['success' => 'Actualizado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    
    public function borrar($id)
    {
        if (DB::table('tipodoc')->where('id', $id)->delete()) {
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => "Hubo un fallo"], 404);
        }
    }





    ////CONSULTA BD SIADEG
    /*
    public function getDep(Request $request) {
        // Conexión a la primera base de datos
        $req = DB::connection('sqlsrv2')->select('SELECT * FROM Dependencia WHERE Descripcion LIKE ?', ['%'.$request->id.'%']);
    
        foreach ($req as $row) {
            // Conexión a la segunda base de datos
            $dependencia = DB::connection('sqlsrv2')->select('SELECT Descripcion, CodDepSuperior FROM Dependencia WHERE CodDependencia = ?', [$row->CodDependencia]);
    
            if (!empty($dependencia)) {
                $dep = $dependencia[0];
                $row->dep_superior = $dep->Descripcion;
    
                $superior = DB::connection('sqlsrv2')->select('SELECT Descripcion FROM Dependencia WHERE CodDependencia = ?', [$dep->CodDepSuperior]);
    
                if (!empty($superior)) {
                    $row->dep_superior = $superior[0]->Descripcion;
                }
            }
        }
    
        return response()->json($req);
    }*/

    public function getDep() {
        // Conexión a la primera base de datos y obtención de todas las dependencias
        $dependencias = DB::connection('sqlsrv2')->select('SELECT * FROM Dependencia');
    
        // Primera pasada: Insertar todas las áreas sin dependencias
        foreach ($dependencias as $row) {
            // Insertar o actualizar los datos en la tabla `area` sin dependencias
            DB::table('area')->updateOrInsert(
                ['nombre' => $row->Descripcion],
                [ 'dependencia' => null,
                    'estado' => 1
                ],
            );
        }
    
        // Segunda pasada: Actualizar las dependencias
        foreach ($dependencias as $row) {
            $dependenciaId = $this->getDependenciaId($row->CodDependencia);
            $superiorId = $this->getDependenciaId($row->CodDepSuperior);
    
            // Actualizar la dependencia con el ID de su superior
            DB::table('area')->where('id', $dependenciaId)->update([
                'dependencia' => $superiorId
            ]);
        }
        return response()->json(['message' => 'Dependencias sincronizadas correctamente']);
    }
    
    // Función para obtener el ID de dependencia con base en `CodDependencia`
    private function getDependenciaId($CodDependencia) {
        if (!$CodDependencia) {
            return null; // Si no hay dependencia, retorna null
        }
    
        $dependencia = DB::connection('sqlsrv2')->select('SELECT Descripcion FROM Dependencia WHERE CodDependencia = ?', [$CodDependencia]);
    
        if (!empty($dependencia)) {
            $descripcion = $dependencia[0]->Descripcion;
    
            // Busca el ID de la dependencia en la tabla `area`
            $area = DB::table('area')->where('nombre', $descripcion)->first();
            return $area ? $area->id : null;
        }
    
        return null;
    }
    







    
    public function getPro(Request $request) {
        $req = DB::connection('sqlsrv2')->select('SELECT * FROM ProySS WHERE DescripcionSS LIKE ?', ['%'.$request->id.'%']);
        
        foreach ($req as $row) {
            $dependencia = DB::connection('sqlsrv2')->select('SELECT Descripcion, CodDepSuperior FROM Dependencia WHERE CodDependencia = ?', [$row->CodDependencia]);
            if (!empty($dependencia)) {
                $dep = $dependencia[0];
                $row->dep_superior = $dep->Descripcion;
    
                $superior = DB::connection('sqlsrv2')->select('SELECT Descripcion FROM Dependencia WHERE CodDependencia = ?', [$dep->CodDepSuperior]);
    
                if (!empty($superior)) {
                    $row->dep_superior = $superior[0]->Descripcion;
                }
            }
        }
        return $req;
    }





}
