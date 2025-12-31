<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Distrito
 * 
 * @property int $id
 * @property string $nombre
 *
 * @package App\Models
 */
class Distrito extends Model
{
	protected $table = 'tipo_personal';
	public $timestamps = false;

	protected $casts = [
		'provincia_id' => 'int'
	];

	protected $fillable = [
		'nombre'
	];
}
