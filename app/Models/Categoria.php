<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Categoria
 * 
 * @property int $id
 * @property string|null $nombre
 * @property string|null $clave
 *
 * @package App\Models
 */
class Categoria extends Model
{
	protected $table = 'categorias';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'clave'
	];
}
