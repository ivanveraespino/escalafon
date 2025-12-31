<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Estudio
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $nivel_educacion
 * @property string|null $estado
 * @property string|null $centroestudios
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * @property string|null $Especialidad
 * @property string|null $GradoAcademico
 * @property string|null $Segunda
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Estudios extends Model
{
	protected $table = 'estudios';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int',
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime'
	];

	protected $fillable = [
		'personal_id',
		'nivel_educacion',
		'centroestudios',
		'especialidad',
		'fecha_ini',
		'fecha_fin',
		'GradoAcademico',
		'idtd',
		'archivo',
		'nro_colegiatura',
		'archivo_colegiatura',
		'nombredoc',
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
