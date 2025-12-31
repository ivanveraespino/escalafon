<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Sancion;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use DB;

class OperacionesController extends Controller
{
    public function index()
    {
        $tdoc = DB::table('tipodoc')->get();
        $reg = DB::table('regimen')->get();
        return view('Operaciones.index',compact('tdoc','reg'));
    }
    
}
