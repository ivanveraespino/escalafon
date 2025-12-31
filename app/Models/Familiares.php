<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Familiare
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $apaterno
 * @property string|null $amaterno
 * @property string|null $nombres
 * @property string|null $parentesco
 * @property Carbon|null $fechanacimiento
 * @property string|null $lugarlaboral
 * @property string|null $ocupacion
 * @property string|null $estadocivil
 * @property string|null $emergencia
 * @property string|null $direccion
 * @property string|null $telefono
 * @property string|null $vive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Familiares extends Model
{
	protected $table = 'familiares';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int',
		'fechanacimiento' => 'datetime'
	];

	protected $fillable = [
		'personal_id',
		'apaterno',
		'amaterno',
		'nombres',
		'parentesco',
		'derecho_habiente',
		'fechanacimiento',
		'idtd',
		'id_tipodocvin',
		'lugarlaboral',
		'ocupacion',
		'estadocivil',
		'emergencia',
		'archivo',
		'archivo_vinculo',
		'direccion',
		'telefono',
		'vive',
		'docid',
		'nroid'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
