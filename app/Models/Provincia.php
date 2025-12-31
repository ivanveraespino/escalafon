<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Provincia
 * 
 * @property int $id
 * @property string|null $nombre
 * @property string|null $ubigeo
 * @property int $departamento_id
 * @property int|null $activo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Provincia extends Model
{
	use SoftDeletes;
	protected $table = 'provincias';

	protected $casts = [
		'departamento_id' => 'int',
		'activo' => 'int'
	];

	protected $fillable = [
		'nombre',
		'ubigeo',
		'departamento_id',
		'activo'
	];
}
