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
 * @property int $provincia_id
 *
 * @package App\Models
 */
class Distrito extends Model
{
	protected $table = 'distritos';
	public $timestamps = false;

	protected $casts = [
		'provincia_id' => 'int'
	];

	protected $fillable = [
		'nombre',
		'provincia_id'
	];
}
