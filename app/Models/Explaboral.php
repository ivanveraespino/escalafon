<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Explaboral
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $entidad
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * @property string|null $cargo
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Explaboral extends Model
{
	protected $table = 'explaboral';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime'
	];

	protected $fillable = [
		'personal_id',
		'entidad',
		'tipo_entidad',
		'fecha_ini',
		'fecha_fin',
		'cargo',
		'idtd',
		'nrodoc',
		'archivo'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
