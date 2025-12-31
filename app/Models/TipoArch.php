<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoArchivo
 * 
 * @property int $id
 * @property string|null $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TipoArch extends Model
{
	protected $table = 'tipo_archivo';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $fillable = [
		'nombre'
	];
}
