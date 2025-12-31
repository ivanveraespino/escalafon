<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reconocimiento
 * 
 * @property int $id
 * @property int|null $idvinculo
 * @property int|null $idtd
 * @property string|null $nrodoc
 * @property string|null $descripcion
 * @property Carbon|null $fechadoc
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 
 *
 * @package App\Models
 */
class Reconocimientos extends Model
{
	protected $table = 'reconocimientos';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'idtd' => 'int',
	];

	protected $fillable = [
		'idvinculo',
		'institucion',
		'idtd',
		'nrodoc',
		'descripcion',
		'fecharecon',
		'archivo'
	];
	
}
