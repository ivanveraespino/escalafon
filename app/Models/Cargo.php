<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Cargo extends Model implements Auditable

{
	use \OwenIt\Auditing\Auditable;
	protected $table = 'cargo';
	protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

	protected $fillable = [
		'id',
		'nombre',
		'estado',
	];
}
