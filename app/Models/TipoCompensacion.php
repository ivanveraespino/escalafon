<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoCompensacion
 * 
 * @property int $id
 * @property string|null $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */

class TipoCompensacion extends Model
{ 
    protected $table = 'tipo_compensacion';
	protected $primaryKey = 'id';
	protected $dateFormat = 'd/m/Y H:i:s'; 
    
    protected $fillable = [
		'nombre'

	];
}
