<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Archivo
 * 
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $clave
 * @property int|null $peso
 * @property string|null $nombre
 * @property int|null $nro_folio
 * @property string|null $file
 * @property string|null $extension
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Archivo extends Model 
{
	protected $table = 'archivo';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $casts = [
		'personal_id' => 'int',
		'peso' => 'int',
		'nro_folio' => 'int'
	];

	protected $fillable = [
		'personal_id',
		'peso',
		'nombre',
		'nro_folio',
		'data_archivo',
		'extension'
	];


	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
