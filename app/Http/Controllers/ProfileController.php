<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Support\Facades\Hash;
use DB;
use Yajra\Datatables\Datatables;

class ProfileController extends Controller
{
    //
    public function index(){
        $users=User::all();
         $admisibles=DB::table('personal as p')
            ->join('vinculos','p.id_personal','=','vinculos.personal_id')
            ->leftJoin('users','p.id_personal','=','users.id_personal')
            ->where('vinculos.id_unidad_organica','303')
            ->orderBy('p.apaterno','desc')
            ->select('p.*','vinculos.fecha_fin')
            ->get();

         $admisibles=DB::select('EXEC posiblesUsuarios');
        return view('Profile.index',compact('users','admisibles'));
    }

    public function getProfiles(){
        $resultados=User::select('name','email','created_at','updated_at','expiration')->get();
        return Datatables::of($resultados)->make(true);
    }
    public function saveUser(Request $request){
        $iduser = $request->input('iduser');
        $usuario=DB::table('personal')->where('id_personal','=',$iduser)->first();
        $fechafin=DB::table('vinculos')->where('personal_id','=',$iduser)->first();
        if($usuario->Correo){
            return User::create(['name'=>$usuario->Nombres." ".$usuario->Apaterno." ".$usuario->Amaterno,
                'email'=>$usuario->Correo,
                'password'=>Hash::make($usuario->nro_documento_id),
                'expiration'=>$fechafin->fecha_fin,
                'id_personal'=>$usuario->id_personal
                ]);
        }
        else {
            $correo=$usuario->Nombres;
            $correo=$correo[0].$usuario->Apaterno.'@mplc';
            $correo=strtolower(rtrim($correo));
            return User::create(['name'=>$usuario->Nombres." ".$usuario->Apaterno." ".$usuario->Amaterno,
                'email'=>$correo,
                'password'=>Hash::make($usuario->nro_documento_id),
                'expiration'=>$fechafin->fecha_fin,
                'id_personal'=>$usuario->id_personal,
                ]);
        }
    }
    public function expireUser(Request $request){
        $iduser = $request->input('id');
         DB::select('EXEC expirarUsuario ?', array($iduser));
         return "Ok";
    }

    public function expandUser(Request $request){
        $iduser = $request->input('id');
         DB::select('EXEC ampliarUsuario ?', array($iduser));
         return "Ok";
    }
}
