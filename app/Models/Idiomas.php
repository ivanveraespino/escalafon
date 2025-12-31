<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Idioma
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $idioma
 * @property string|null $lectura
 * @property string|null $habla
 * @property string|null $escritura
 * @property string|null $archivo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Idiomas extends Model
{
	protected $table = 'idiomas';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int'
	];

	protected $fillable = [
		'personal_id',
		'idioma',
		'lectura',
		'habla',
		'escritura',
		'idtd',
		'archivo',
		'nombredoc'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
