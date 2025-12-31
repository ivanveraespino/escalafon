<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TiempoServicio
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $descripcion
 * @property int|null $idtd
 * @property string|null $nro_doc
 * @property Carbon|null $fecha_doc
 * @property int|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class TiempoServicio extends Model
{
	protected $table = 'tiempo_servicio';
    protected $dateFormat = 'd/m/Y H:i:s'; // Formato típico en español para SQL Server

	protected $casts = [
		'personal_id' => 'int',
		'idtd' => 'int',
		'fecha_doc' => 'datetime',
		'archivo' => 'int',
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime'
	];

	protected $fillable = [
		'personal_id',
		'descripcion',
		'idtd',
		'nrodoc',
		'fecha_doc',
		'archivo',
		'fecha_ini',
		'fecha_fin'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
