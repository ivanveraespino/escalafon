<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vacacione
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property int|null $idtd
 * @property string|null $nrodoc
 * @property string|null $periodo
 * @property string|null $mes
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * @property string|null $obs
 * @property int|null $dias
 * @property Carbon|null $fechadoc
 * @property string|null $archivo
 * @property string|null $suspencion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Vacaciones extends Model
{
	protected $table = 'vacaciones';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'idvinculo' => 'int',
		'idtd' => 'int',
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime',
		'dias' => 'int',
		'fechadoc' => 'datetime'
	];

	protected $fillable = [
		'idvinculo',
		'idtd',
		'nrodoc',
		'periodo',
		'mes',
		'fecha_ini',
		'fecha_fin',
		'observaciones',
		'dias',
		'adelantado',
		'fechadoc',
		'archivo',
		'suspension'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
