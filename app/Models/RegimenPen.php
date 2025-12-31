<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RegimenPensionario
 * 
 * @property int $id
 * @property string|null $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RegimenPen extends Model
{
	protected $table = 'regimen_pensionario';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $fillable = [
		'nombre'
	];
}
