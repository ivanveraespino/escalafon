<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sancione
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property int|null $idtd
 * @property string|null $nrodoc
 * @property string|null $descripcion
 * @property Carbon|null $desde
 * @property Carbon|null $hasta
 * @property Carbon|null $fechadoc
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Sancion extends Model
{
	protected $table = 'sanciones';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; // Formato típico en español para SQL Server

	protected $casts = [
		'idtd' => 'int',
		'fechadoc' => 'datetime',
	];

	protected $fillable = [
		'idvinculo',
		'idtd',
		'nrodoc',
		'descripcion',
		'dias_san',
		'fechadoc',
		'archivo',
		'fecha_ini',
		'fecha_fin',
		'tiposancion'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}

