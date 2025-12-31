<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Regimen;
use App\Models\CondicionLaboral;
use App\Models\Tipodoc;
use App\Models\RegimenPen;
use App\Models\TipoVia;
use Illuminate\Support\Facades\Blade;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

     
    public function boot(): void
    {

        Blade::component('boton-salir', 'components.boton-salir.blade.php');
        $dep = Departamento::all();
        $pro = Provincia::all();
        $dis = Distrito::all();
        $reg = Regimen::all();
        $conlab = CondicionLaboral::all();
        $tdoc = Tipodoc::all();
        $rp = RegimenPen::all();
        $dtv = TipoVia::all();
        $mcese = DB::table('motivo_fin_vinculo')->get();
        $tpersonal = DB::table('tipo_personal')->get(); 
        View::share([
            'dep' => $dep,
            'pro' => $pro,
            'dis' => $dis,
            'reg' => $reg,
            'conlab' => $conlab,
            'tdoc' => $tdoc,
            'rp' => $rp,
            'dtv' => $dtv,
            'mcese' => $mcese,
            'tpersonal' => $tpersonal,
        ]);
    }


}

