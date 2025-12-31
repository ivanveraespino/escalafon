<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoVium
 * 
 * @property int $id
 * @property string|null $nombre
 *
 * @package App\Models
 */
class TipoVia extends Model
{
	protected $table = 'tipo_via';
	public $timestamps = false;
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $fillable = [
		'nombre'
	];
}
