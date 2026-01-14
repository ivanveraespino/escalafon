<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Personal
 *
 * @property int $id_personal
 * @property string|null $nro_documento_id
 * @property string|null $Apaterno
 * @property string|null $Amaterno
 * @property string|null $Nombres
 * @property string|null $fpersonal
 * @property Carbon|null $FechaNacimiento
 * @property string|null $lprocedencia
 * @property string|null $NroColegiatura
 * @property string|null $NroRuc
 * @property string|null $NroEssalud
 * @property string|null $CentroEssalud
 * @property string|null $GrupoSanguineo
 * @property string|null $NroTelefono
 * @property string|null $NroCelular
 * @property string|null $Correo
 * @property string|null $EstadoCivil
 * @property string|null $sexo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $id_tipo_personal
 * @property string|null $ocupacion
 * @property string|null $afiliacion_salud
 * @property int|null $archivo
 * @property int|null $id_regimen_modalidad
 * @property int|null $id_regimen
 * @property string|null $id_identificacion
 * @property string|null $afp
 * @property string|null $id_regimenp
 * @property string|null $discapacidad
 * @property string|null $ffaa
 * @property string|null $urldj
 *
 * @property Collection|Archivo[] $archivos
 * @property Collection|Compensacione[] $compensaciones
 * @property Collection|Vinculo[] $vinculos
 * @property Collection|CronogramaVac[] $cronograma_vacs
 * @property Domicilio $domicilio
 * @property Collection|Estudio[] $estudios
 * @property Collection|EstudiosExtra[] $estudios_especializacion
 * @property Collection|Explaboral[] $explaborals
 * @property Collection|Familiare[] $familiares
 * @property Collection|Idioma[] $idiomas
 * @property Collection|Licencia[] $licencias
 * @property Collection|Nombramiento[] $nombramientos
 * @property Collection|Permiso[] $permisos
 * @property Collection|Reconocimiento[] $reconocimientos
 * @property Collection|Rotacione[] $movimientos
 * @property Collection|Sancione[] $sanciones
 * @property Collection|TiempoServicio[] $tiempo_servicios
 * @property Collection|Vacacione[] $vacaciones
 *
 * @package App\Models
 */
class Personal extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $table = 'personal';
	protected $primaryKey = 'id_personal';
    protected $dateFormat = 'd/m/Y H:i:s';

	protected $casts = [
		'FechaNacimiento' => 'datetime',
		'archivo' => 'int',
		'id_regimen_modalidad' => 'int',
		'id_regimen' => 'int'
	];

	protected $fillable = [
		'nro_documento_id',
		'Apaterno',
		'Amaterno',
		'Nombres',
		'FechaNacimiento',
		'lprocedencia',
		'NroColegiatura',
		'NroRuc',
		'NroEssalud',
		'CentroEssalud',
		'GrupoSanguineo',
		'NroTelefono',
		'NroCelular',
		'Correo',
		'EstadoCivil',
		'sexo',
		'id_tipo_personal',
		'ocupacion',
		'afiliacion_salud',
		'archivo',
		'id_regimen_modalidad',
		'id_regimen',
		'id_identificacion',
		'afp',
		'id_regimenp',
		'discapacidad',
		'ffaa',
        'estado',
		'foto',
		'urlgeneral',
		'verificador',
		'urldj'
	];

	public function archivos()
	{
		return $this->hasMany(Archivo::class, 'personal_id');
	}

	public function compensaciones()
	{
		return $this->hasMany(Compensacione::class, 'personal_id');
	}

	public function vinculos()
	{
		return $this->hasMany(Vinculo::class, 'personal_id');
	}

	public function cronograma_vacs()
	{
		return $this->hasMany(CronogramaVac::class, 'personal_id');
	}

	public function domicilio()
	{
		return $this->hasOne(Domicilio::class, 'personal_id');
	}

	public function estudios()
	{
		return $this->hasMany(Estudio::class, 'personal_id');
	}

	public function estudios_especializacion()
	{
		return $this->hasMany(EstudiosExtra::class, 'personal_id');
	}

	public function explaborals()
	{
		return $this->hasMany(Explaboral::class, 'personal_id');
	}

	public function familiares()
	{
		return $this->hasMany(Familiare::class, 'personal_id');
	}

	public function idiomas()
	{
		return $this->hasMany(Idioma::class, 'personal_id');
	}

	public function licencias()
	{
		return $this->hasMany(Licencia::class, 'personal_id');
	}

	public function nombramientos()
	{
		return $this->hasMany(Nombramiento::class, 'personal_id');
	}

	public function permisos()
	{
		return $this->hasMany(Permiso::class, 'personal_id');
	}

	public function reconocimientos()
	{
		return $this->hasMany(Reconocimiento::class, 'personal_id');
	}

	public function movimientos()
	{
		return $this->hasMany(Rotacione::class, 'personal_id');
	}

	public function sanciones()
	{
		return $this->hasMany(Sancione::class, 'personal_id');
	}

	public function tiempo_servicios()
	{
		return $this->hasMany(TiempoServicio::class, 'personal_id');
	}

	public function vacaciones()
	{
		return $this->hasMany(Vacacione::class, 'personal_id');
	}
}
