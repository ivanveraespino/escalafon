<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Domicilio
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $dactual
 * @property string|null $tipodom
 * @property int|null $iddep
 * @property int|null $idpro
 * @property int|null $iddis
 * @property string|null $numero
 * @property string|null $interior
 * @property string|null $referencia
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Domicilio extends Model
{
	protected $table = 'domicilio';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int',
		'iddep' => 'int',
		'idpro' => 'int',
		'iddis' => 'int'
	];

	protected $fillable = [
		'personal_id',
		'dactual',
		'tipodom',
		'iddep',
		'idpro',
		'iddis',
		'numero',
		'interior',
		'referencia'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
