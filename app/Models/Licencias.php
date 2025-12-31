<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Licencia
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property int|null $idtd
 * @property string|null $nrodoc
 * @property int|null $id_descripcion
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * @property string|null $congoce
 * @property string|null $periodo
 * @property string|null $acuentavac
 * @property Carbon|null $fechadoc
 * @property int|null $dias
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $mes
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Licencias extends Model
{
	protected $table = 'licencias';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int',
		'idtd' => 'int',
		'id_descripcion' => 'int',
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime',
		'fechadoc' => 'datetime',
		'dias' => 'int'
	];

	protected $fillable = [
		'personal_id',
		'idtd',
		'nrodoc',
		'descripcion',
		'fecha_ini',
		'fecha_fin',
		'congoce',
		'periodo',
		'observaciones',
		'licencias',
		'acuentavac',
		'fechadoc',
		'adelantado',
		'dias',
		'archivo',
		'mes',
		'nombredoc'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
