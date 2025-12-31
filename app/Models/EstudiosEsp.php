<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstudiosExtra
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $nombre
 * @property string|null $centroestudios
 * @property int|null $horas
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class EstudiosEsp extends Model
{
	protected $table = 'estudios_especializacion';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int',
		'horas' => 'int',
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime'
	];

	protected $fillable = [
		'personal_id',
		'nombre',
		'centroestudios',
		'horas',
		'idtd',
		'archivo',
		'fecha_ini',
		'fecha_fin',
		'nombredoc'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
