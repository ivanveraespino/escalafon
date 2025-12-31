<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipodoc
 * 
 * @property int $id
 * @property string|null $tipo
 * @property string|null $nombre
 * @property string|null $categoria
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TipoMovimiento extends Model
{
	protected $table = 'tipo_movimiento';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $fillable = [
		'tipo',
		'nombre',
	];
}
