<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CondicionLaboral
 * 
 * @property int $id
 * @property string|null $nombre
 * @property string|null $abreviatura
 * @property string|null $descripcion_regimen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $tipo_personal
 *
 * @package App\Models
 */
class CondicionLaboral extends Model
{
	protected $table = 'condicion_laboral';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $fillable = [
		'nombre',
		'abreviatura',
		'descripcion_regimen',
		'tipo_personal'
	];
}
