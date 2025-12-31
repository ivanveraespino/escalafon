<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BiometricoController;
use App\Http\Controllers\VacacionesController;
use App\Http\Controllers\CronogramaController;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\SancionesController;
use App\Http\Controllers\LegajoController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\RegimenController;
use App\Http\Controllers\RegimenPenController;
use App\Http\Controllers\RegimenMController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\EstudiosexController;
use App\Http\Controllers\CeseController;
use App\Http\Controllers\ArchivosController;
use App\Http\Controllers\ArchivosAdjuntosController;
use App\Http\Controllers\NombramientoController;
use App\Http\Controllers\ReconocimientosController;
use App\Http\Controllers\LicenciasController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\VLPController;
use App\Http\Controllers\OperacionesController;
use App\Http\Controllers\CompensacionesController;
use App\Http\Controllers\ModalidadController;
use App\Http\Controllers\TipoArchivoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\EncargaturaController;
use App\Http\Controllers\ReporteTiempoController;
use App\Http\Controllers\TiempoServicioController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\EstudiosController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\FamiliaresController;
use App\Http\Controllers\VinculoController;
use App\Http\Controllers\ColegiaturaController;
use App\Http\Controllers\AdendaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\RepositorioController;
use App\Models\Regimen;
use App\Models\RegimenPen;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();


Route::get('/mostrara_vac_rec', [HomeController::class, 'mostrara_vac_rec'])->name('mostrara_vac_rec');
Route::get('repotiempo/{fecha?}', [HomeController::class, 'getRepoTiempo'])->name('repotiempo');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('trabajos', TrabajoController::class);

    Route::get('datos_personal/{id?}/{estado?}', [EmployeeController::class, 'datos_personal'])->name('datos_personal');
    Route::get('/nuevo-personal', [PersonalController::class, 'nuevoPersonal'])->name('nuevoPersonal');
    Route::post('/tipopersonal', [PersonalController::class, 'agregarTipoPersonal'])->name('tipopersonal.add');
    Route::post('/nueva-via', [PersonalController::class, 'agregarNuevaVia'])->name('nuevaVia');
    Route::post('/nueva-compensacion', [PersonalController::class, 'agregarNuevaCompensacion'])->name('agregarNuevaCompensacion');
    Route::post('/nuevo-regimen', [RegimenController::class, 'nuevoRegimen'])->name('nuevoRegimen');
    Route::post('/nueva-condicion-lab', [PersonalController::class, 'nuevaCondicionLab'])->name('nuevaCondicionLab');
    Route::post('/nuevo-regimen-pen', [RegimenPenController::class, 'nuevoRegimenPen'])->name('nuevoRegimenPen');
    Route::post('guardar-personal-nuevo', [PersonalController::class, 'guardarNuevo'])->name('guardarPersonalNuevo');
    Route::get('edicion-personal', [PersonalController::class, 'edicionPersonal'])->name('editarPersonal');
    Route::post('guardar-edicion/{id}', [PersonalController::class, 'guardarEdicion'])->name('guardarEdicion');
    Route::post('/consulta-vacaciones', [PersonalController::class, 'consultarVacaciones'])->name('consultarVacaciones');
    Route::post('/consulta-licencias', [PersonalController::class, 'consultarLicencias'])->name('consultarLicencias');
    Route::post('/consulta-permisos', [PersonalController::class, 'consultarPermisos'])->name('consultarPermisos');
    Route::get('anular-personal/{id}', [PersonalController::class, 'anularPersonal'])->name('anularPersonal');

    Route::resource('employees', EmployeeController::class);
    Route::resource('justificaciones', JustificacionController::class);
    Route::resource('Regimen', RegimenController::class);
    Route::resource('RegimenPen', RegimenPenController::class);
    Route::resource('Regimen_modalidad', RegimenMController::class);
    Route::resource('Modalidad', ModalidadController::class);
    Route::resource('TipoArch', TipoArchivoController::class);
    Route::resource('Area', AreaController::class);
    Route::resource('Infor', AreaController::class);
    Route::resource('Cargo', CargoController::class);
    Route::resource('Ubigeo', UbigeoController::class);
    Route::resource('Documento', DocumentoController::class);
    Route::resource('Reporte', ReporteController::class);
    Route::resource('Cese', CeseController::class);
    Route::resource('Archivos', ArchivosController::class);
    Route::resource('Movimiento', VLPController::class);
    Route::resource('Operaciones', OperacionesController::class);
    Route::resource('ReporteTiempo', ReporteTiempoController::class);
    Route::resource('legajoDatos', legajoDatos::class);
    Route::resource('permisos_crud', PermisosController::class)->names('permisos_crud');
    Route::resource('licencias_crud', LicenciasController::class)->names('licencias_crud');
    Route::resource('cronograma_crud', CronogramaController::class)->names('cronograma_crud');
    Route::resource('reconocimientos_crud', ReconocimientosController::class)->names('reconocimientos_crud');
    Route::resource('experiencia_crud', ExperienciaController::class)->names('experiencia_crud');
    Route::resource('familiares_crud', FamiliaresController::class)->names('familiares_crud');
    Route::resource('idioma_crud', IdiomaController::class)->names('idioma_crud');
    Route::resource('estudios_crud', EstudiosController::class)->names('estudios_crud');
    Route::resource('estudios_complem_crud', EstudiosexController::class)->names('estudios_complem_crud');
    Route::resource('conpensaciones_crud', CompensacionesController::class)->names('conpensaciones_crud');
    Route::resource('vinculo_crud', VinculoController::class)->names('vinculo_crud');
    Route::resource('tiempo_servicio_crud', TiempoServicioController::class)->names('tiempo_servicio_crud');
    Route::resource('movimientos_crud', MovimientosController::class)->names('movimientos_crud');
    Route::resource('vacaciones_crud', VacacionesController::class)->names('vacaciones_crud');
    Route::resource('archivos_adjuntos_crud', ArchivosAdjuntosController::class)->names('archivos_adjuntos_crud');
    Route::resource('sanciones_crud', SancionesController::class)->names('sanciones_crud');
    Route::resource('colegiatura_crud', ColegiaturaController::class)->names('colegiatura_crud');
    Route::resource('adendas_crud', AdendaController::class)->names('adendas_crud');

    Route::get('/LegajoNuevo/{tipo}', [LegajoController::class, 'FichaNueva'])->name('FichaNueva');
    Route::get('obtener_departamentos', [UbigeoController::class, 'obtener_departamentos'])->name('obtener_departamentos');
    Route::get('obtener_provincias_por_departamento', [UbigeoController::class, 'obtener_provincias_por_departamento'])->name('obtener_provincias_por_departamento');
    Route::get('obtener_distritos_por_provincia', [UbigeoController::class, 'obtener_distritos_por_provincia'])->name('obtener_distritos_por_provincia');
    Route::get('/showfiles/{id}', [LegajoController::class, 'showfiles'])->name('showfiles');
    Route::get('/Legajo/{dni}', [LegajoController::class, 'editarFicha'])->name('editarFicha');
    Route::get('/ReporteFicha/{dni}', [LegajoController::class, 'reportFicha'])->name('reportFicha');
    Route::post('/InformeEsc', [LegajoController::class, 'informe'])->name('InformeEsc');
    Route::get('buscarpersonal/{dni}', [LegajoController::class, 'buscarpersonal'])->name('buscarpersonal');
    Route::get('/mostrardp/{id}', [LegajoController::class, 'mostrardp'])->name('mostrardp');
    Route::get('/mostrardpqr/{id?}', [LegajoController::class, 'mostrardpqr'])->name('mostrardpqr');
    Route::get('/getRepoTiempo', [ReporteTiempoController::class, 'getRepoTiempo'])->name('getRepoTiempo');
    //UNIDAD ORGANICA ACTUAL
    Route::get('get_uo_vigente/{id?}', [LegajoController::class, 'get_uo_vigente'])->name('get_uo_vigente');
    Route::post('/generar-reporte', [ReporteController::class, 'generarReporte'])->name('generarReporte');
    Route::get('getCargo_list', [ReporteController::class, 'getCargo_list'])->name('getCargo_list');
    Route::get('getAreas_list', [ReporteController::class, 'getAreas_list'])->name('getAreas_list');
    Route::get('getRegimen_list', [ReporteController::class, 'getRegimen_list'])->name('getRegimen_list');
    Route::get('getCondicion_list', [ReporteController::class, 'getCondicion_list'])->name('getCondicion_list');
    Route::get('getMovimiento_list', [ReporteController::class, 'getMovimiento_list'])->name('getMovimiento_list');
    Route::get('getPersonal_list/{id?}', [ReporteController::class, 'getPersonal_list'])->name('getPersonal_list');
    Route::get('get_modalidad_list', [ReporteController::class, 'get_modalidad_list'])->name('get_modalidad_list');
    Route::get('getMotivofin_list', [ReporteController::class, 'getMotivofin_list'])->name('getMotivofin_list');
    Route::get('getMeses', [ReporteController::class, 'getMeses'])->name('getMeses');
    Route::get('/VerificarIntegridad/{id}', [LegajoController::class, 'VerificarIntegridad'])->name('VerificarIntegridad');
    Route::post('/editarPersonal/{dni?}', [LegajoController::class, 'editarPersonal'])->name('editarPersonal');
    Route::post('/guardarPersonal', [LegajoController::class, 'guardarPersonal'])->name('guardarPersonal');
    Route::get('/borrarPersonal/{dni?}', [LegajoController::class, 'borrarPersonal'])->name('borrarPersonal');
    Route::post('addAdenda', [LegajoController::class, 'addAdenda'])->name('addAdenda');
    Route::get('/borrarAdenda/{id}', [LegajoController::class, 'borrarAdenda'])->name('borrarAdenda');
    Route::post('/actualizar-personal', [LegajoController::class, 'editarFicha'])->name('actualizar.personal');
    Route::get('/ncampo/{id}/{tipo?}', [LegajoController::class, 'ncampo']);
    Route::get('/campo/{id}/{dni}/{regimen}', [LegajoController::class, 'campo']);
    Route::get('getCargosE/{dni}', [LegajoController::class, 'getCargosE'])->name('getCargosE');
    Route::get('getRotacion', [LegajoController::class, 'getRotacion'])->name('getRotacion');
    Route::get('getSancion', [LegajoController::class, 'getSancion'])->name('getSancion');
    Route::get('getNombramiento', [LegajoController::class, 'getNombramiento'])->name('getNombramiento');
    Route::get('getMovimientos/{periodo?}', [VLPController::class, 'getMovimientos'])->name('getMovimientos');
    Route::get('getCrono', [VLPController::class, 'getCrono'])->name('getCrono');
    Route::get('buscarUsuarios', [VLPController::class, 'buscarUsuarios'])->name('buscarUsuarios');
    Route::get('getContrato/{id?}', [LegajoController::class, 'getContrato'])->name('getContrato');
    Route::get('get_form_contrato', [FormularioController::class, 'get_form_contrato'])->name('get_form_contrato');
    Route::get('get_form_nombramiento', [FormularioController::class, 'get_form_nombramiento'])->name('get_form_nombramiento');
    Route::get('buscarCronograma', [VLPController::class, 'buscarCronograma'])->name('buscarCronograma');
    Route::get('getAdendas/{id?}', [LegajoController::class, 'obtenerAdendas'])->name('getAdendas');
    Route::get('SumaCronograma', [VLPController::class, 'SumaCronograma'])->name('SumaCronograma');
    Route::post('guardarn', [NombramientoController::class, 'guardar'])->name('guardarn');
    Route::post('importExcelCron', [CronogramaController::class, 'importExcelCron'])->name('importExcelCron');
    Route::get('getCampomo', [ModalidadController::class, 'getCampomo'])->name('getCampomo');
    Route::post('guardarmo', [ModalidadController::class, 'guardar'])->name('guardarmo');
    Route::get('/mostrarmo/{id}', [ModalidadController::class, 'mostrar'])->name('mostrarmo');
    Route::post('/editmo/{id}', [ModalidadController::class, 'edit'])->name('editmo');
    Route::get('/borrarmo/{id}', [ModalidadController::class, 'borrar'])->name('borrarmo');
    Route::get('/mostrarallmo/{id?}', [ModalidadController::class, 'getCampo'])->name('mostrarallmo');
    Route::get('getCampota', [TipoArchivoController::class, 'getCampota'])->name('getCampota');
    Route::post('guardarta', [TipoArchivoController::class, 'guardar'])->name('guardarta');
    Route::get('/mostrarta/{id}', [TipoArchivoController::class, 'mostrar'])->name('mostrarta');
    Route::post('/editta/{id}', [TipoArchivoController::class, 'edit'])->name('editta');
    Route::get('/borrarta/{id}', [TipoArchivoController::class, 'borrar'])->name('borrarta');
    Route::get('getCampoare', [AreaController::class, 'getall'])->name('getCampoare');
    Route::post('guardarare', [AreaController::class, 'guardar'])->name('guardarare');
    Route::get('/mostrarare/{id?}', [AreaController::class, 'mostrar'])->name('mostrarare');
    Route::post('/editare/{id?}', [AreaController::class, 'edit'])->name('editare');
    Route::get('/borrarare/{id?}', [AreaController::class, 'borrar'])->name('borrarare');
    Route::post('/agregar-area', [AreaController::class, 'agregarArea'])->name('nuevaArea');
    Route::get('getCampocarg', [CargoController::class, 'getall'])->name('getCampocarg');
    Route::post('guardarcarg', [CargoController::class, 'guardar'])->name('guardarcarg');
    Route::get('/mostrarcarg/{id?}', [CargoController::class, 'mostrar'])->name('mostrarcarg');
    Route::post('/editcarg/{id?}', [CargoController::class, 'edit'])->name('editcarg');
    Route::get('/borrarcarg/{id?}', [CargoController::class, 'borrar'])->name('borrarcarg');
    Route::get('getFolioPersonal', [ArchivosController::class, 'getFolioPersonal'])->name('getFolioPersonal');
    Route::get('/MostrarArchivos/{id?}', [ArchivosController::class, 'MostrarArchivos'])->name('MostrarArchivos');
    Route::get('/MostrarCategorias', [ArchivosController::class, 'MostrarCategorias'])->name('MostrarCategorias');
    Route::get('/general', [ArchivosController::class, 'general'])->name('general');
    Route::post('guardar_archivos', [ArchivosController::class, 'guardar_archivos'])->name('guardar_archivos');
    Route::get('borrar_archivo/{id?}', [ArchivosController::class, 'borrar_archivo'])->name('borrar_archivo');
    Route::get('getCampo', [RegimenController::class, 'getCampo'])->name('getCampo');
    Route::post('guardarcam', [RegimenController::class, 'guardarcam'])->name('guardarcam');
    Route::get('/mostrarcam/{id}', [RegimenController::class, 'mostrarcam'])->name('mostrarcam');
    Route::post('/editcam/{id}', [RegimenController::class, 'editcam'])->name('editcam');
    Route::get('/borrarcam/{id}', [RegimenController::class, 'borrarcam'])->name('borrarcam');
    Route::get('getCamporm', [RegimenMController::class, 'getCampo'])->name('getCamporm');
    Route::post('guardarrm', [RegimenMController::class, 'guardar'])->name('guardarrm');
    Route::get('/mostrarrm/{id}', [RegimenMController::class, 'mostrar'])->name('mostrarrm');
    Route::post('/editrm/{id}', [RegimenMController::class, 'edit'])->name('editrm');
    Route::get('/borrarrm/{id}', [RegimenMController::class, 'borrar'])->name('borrarrm');
    Route::get('getrp', [RegimenPenController::class, 'getrp'])->name('getrp');
    Route::post('guardarrp', [RegimenPenController::class, 'guardarrp'])->name('guardarrp');
    Route::get('/mostrarrp/{id}', [RegimenPenController::class, 'mostrarrp'])->name('mostrarrp');
    Route::post('/editrp/{id}', [RegimenPenController::class, 'editrp'])->name('editrp');
    Route::get('/borrarrp/{id}', [RegimenPenController::class, 'borrarrp'])->name('borrarrp');
    Route::get('datoscampo/{tipoDocumento?}', [DocumentoController::class, 'datoscampo'])->name('datoscampo');
    Route::post('guardar', [DocumentoController::class, 'guardar'])->name('guardar');
    Route::get('/mostrar/{id}', [DocumentoController::class, 'mostrar'])->name('mostrar');
    Route::post('/edit/{id}', [DocumentoController::class, 'edit'])->name('edit');
    Route::get('/borrar/{id}', [DocumentoController::class, 'borrar'])->name('borrar');
    Route::post('/fileUpload', [DocumentoController::class, 'fileUpload'])->name('fileUpload');
    Route::get('/showTreeView', [DocumentoController::class, 'showTreeView'])->name('showTreeView');
    Route::post('/uploadFile', [DocumentoController::class, 'uploadFile'])->name('uploadFile');
    Route::get('getDep/{id?}', [DocumentoController::class, 'getDep'])->name('getDep');
    Route::get('getPro/{id?}', [DocumentoController::class, 'getPro'])->name('getPro');
    Route::get('getEmployees', [EmployeeController::class, 'getEmployees'])->name('getEmployees');
    Route::get('/archivos-adjuntos/{personalId?}', [EmployeeController::class, 'obtenerArchivosAdjuntos']);
    Route::post('importExcel', [EmployeeController::class, 'importExcel'])->name('importExcel');
    Route::post('replaceDuplicates', [EmployeeController::class, 'replaceDuplicates'])->name('replaceDuplicates');
    Route::get('showperfil/{id}', [EmployeeController::class, 'showperfil'])->name('showperfil');
    Route::get('ValidarPersonal/{id?}', [EmployeeController::class, 'ValidarPersonal'])->name('ValidarPersonal');
    Route::get('buscarEmpleado/{dni}', [EmployeeController::class, 'buscarEmpleado'])->name('buscarEmpleado');
    Route::get('borrarJustificacion/{idJustificacion}', [JustificacionController::class, 'borrarJustificacion'])->name('justificacion.borrar');
    Route::post('legajo/importExcel', [EmployeeController::class, 'importExcel'])->name('importExcel');
    Route::post('legajo/replaceDuplicates', [EmployeeController::class, 'replaceDuplicates'])->name('replaceDuplicates');
    Route::get('legajo/showperfil/{id}', [EmployeeController::class, 'showperfil'])->name('showperfil');
    Route::get('legajo/ValidarPersonal/{id?}', [EmployeeController::class, 'ValidarPersonal'])->name('ValidarPersonal');
    Route::get('legajo/ReporteFicha/{dni}', [LegajoController::class, 'reportFicha'])->name('reportFicha');
    Route::get('descargarAllArchivos/{dni?}', [LegajoController::class, 'descargarAllArchivos'])->name('descargarAllArchivos');
    Route::post('processView', [LegajoController::class, 'processView'])->name('processView');
    Route::get('descargarTodo/{dni?}', [LegajoController::class, 'descargarTodo'])->name('descargarTodo');
    Route::get('legajo/datosPersonales/{id?}', [LegajoController::class, 'viewDatosPersonal'])->name('legajo.datosPersonales');
    Route::get('legajo/familiares/{id?}', [LegajoController::class, 'viewFamiliares'])->name('legajo.familiares');
    Route::get('legajo/vinculo-laboral/{id?}', [LegajoController::class, 'viewVinculos'])->name('legajo.vinculos');
    Route::get('legajo/experiencia-laboral/{id?}', [LegajoController::class, 'viewExperiencia'])->name('legajo.experiencia');
    Route::get('legajo/movimientos/{id?}', [LegajoController::class, 'viewMovimientos'])->name('legajo.movimientos');
    Route::get('legajo/reconocimientos/{id?}/{filter?}', [LegajoController::class, 'viewReconocimientos'])->name('legajo.reconocimientos');
    Route::get('legajo/idiomas/{id?}', [LegajoController::class, 'viewIdiomas'])->name('legajo.idiomas');
    Route::get('legajo/vacaciones/{id?}', [LegajoController::class, 'viewVacaciones'])->name('legajo.vacaciones');
    Route::get('legajo/licencias/{id?}', [LegajoController::class, 'viewLicencias'])->name('legajo.licencias');
    Route::get('legajo/permisos/{id?}', [LegajoController::class, 'viewPermisos'])->name('legajo.permisos');
    Route::get('legajo/compensaciones/{id?}', [LegajoController::class, 'viewCompesaciones'])->name('legajo.compensaciones');
    Route::get('legajo/formacion-academica/{id?}', [LegajoController::class, 'viewFormacion'])->name('legajo.formacion');
    Route::get('legajo/colegiatura/{id?}', [LegajoController::class, 'viewColegiatura'])->name('legajo.colegiatura');
    Route::get('legajo/sanciones/{id?}', [LegajoController::class, 'viewSanciones'])->name('legajo.sanciones');
    Route::get('legajo/estudios-complementarios/{id?}', [LegajoController::class, 'viewEstudiosCom'])->name('legajo.estudiosCom');
    Route::get('legajo/asignacion-tiempos/{id?}', [LegajoController::class, 'viewAsignacionTiempo'])->name('legajo.asignacion');
    Route::get('legajo/otros-archivos/{id?}', [LegajoController::class, 'viewOtrosArchivo'])->name('legajo.ArchivosAdjuntos');
    Route::get('legajo/getEmployees', [EmployeeController::class, 'listEmployees'])->name('listEmployees');
    Route::get('profile/saveUser', [ProfileController::class, 'saveUser'])->name('saveUser');
    Route::get('profile/expireUser', [ProfileController::class, 'expireUser'])->name('expireUser');
    Route::get('profile/expandUser', [ProfileController::class, 'expandUser'])->name('expandUser');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('getProfiles', [ProfileController::class, 'getProfiles'])->name('getProfiles');
    Route::get('/generate-word', [WordController::class, 'generateWord'])->name('generate.word');

    Route::get('/informes', [InformeController::class, 'verReporte'])->name('verReporte');
    Route::get('/generar-informe', [InformeController::class, 'generarInforme'])->name('generarInforme');
    Route::get('/descargar-word', [InformeController::class, 'descargarWord'])->name('descargarWord');
    Route::get('/descargar-informe70', [InformeController::class, 'informe70'])->name('informe70');
    Route::get('/descargar-informe30', [InformeController::class, 'informe30'])->name('informe30');
    Route::get('/descargar-informe25', [InformeController::class, 'informe25'])->name('informe25');
    Route::get('/descargar-archivos', [InformeController::class, 'descargarArchivos'])->name('descargarArchivos');
    Route::get('/datos-personal', [InformeController::class, 'datosPersonal'])->name('datosPersonal');
    Route::get('/generar-fotocheck', [InformeController::class, 'generarFotocheck'])->name('generarFotocheck');
    Route::get('/generar-fotocheck-masivo', [InformeController::class, 'generarFotocheckMasivo'])->name('generarFotocheckMasivo');

    
    Route::post('/subir-carga', [RepositorioController::class, 'subirCarga']);

    Route::get('/datos-vinculo', [VinculoController::class, 'datosVinculo']);

    Route::get('/personal/xxv', [HomeController::class, 'getVeinticincos']);
    Route::get('/personal/xxx', [HomeController::class, 'getTreintas']);
    Route::get('/personal/cesantes', [HomeController::class, 'getCesantes']);
    Route::get('fotocheck-masivo', [InformeController::class, 'fotocheckMasivo'])->name('fotocheckMasivo');

    Route::get('cronogramar-masivo', [CronogramaController::class, 'cronogramarMasivo'])->name('cronogramarMasivo');
    Route::get('cronogramar-individual', [CronogramaController::class, 'cronogramarIndividual'])->name('cronogramarIndividual');
    
    Route::get('guardar-cronograma-masivo', [CronogramaController::class, 'guardarCronogramaMasivo'])->name('guardarCronogramaMasivo');

    Route::get('/consultar-individuo/{id}', [CronogramaController::class, 'consultarIndividuo']);
    Route::POST('/guardar-individual', [CronogramaController::class, 'guardarIndividual']);


});

Route::get('/users2', [UserController::class, 'index2'])->name('users.index2');
Route::get('/nuevo-celular', [PersonalController::class, 'ingresoNumero'])->name('ingresoNumero');
Route::get('/validar-número', [PersonalController::class, 'validarNumero'])->name('validarNumero');
Route::get('/ingreso-masivo', [PersonalController::class, 'setPersonal'])->name('setPersonal');
Route::get('/edicion-ingreso-masivo', [PersonalController::class, 'editSetPersonal'])->name('editSetPersonal');
Route::post('/guardar-ingreso-masivo', [PersonalController::class, 'guardarIngresoMasivo'])->name('guardarIngresoMasivo');
Route::post('/consulta-existencia', [PersonalController::class, 'consultaExistencia'])->name('consultarDNI');
Route::get('/agradecimiento', [PersonalController::class, 'agradecimiento'])->name('agradecimiento');
Route::post('/nuevo-cargo', [PersonalController::class, 'agregarNuevoCargo'])->name('agregarNuevoCargo');
Route::get('consultar-provincia', [PersonalController::class, 'obtenerProvincias'])->name('consultarProvincia');
Route::get('consultar-distritos', [PersonalController::class, 'obtenerDistritos'])->name('consultarDistrito');
Route::post('/subir-archivo', [RepositorioController::class, 'subirArchivo']);
Route::get('/perfil', [InformeController::class, 'perfilPersonal'])->name('perfilPersonal');

