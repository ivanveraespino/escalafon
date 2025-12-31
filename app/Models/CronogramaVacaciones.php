<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaVacaciones extends Model
{
    use HasFactory;

    protected $table='cronograma_vac';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

    protected $fillable = [
        'id',
        'idvinculo',
        'idtd',        
        'nrodoc',
        'mes',
        'fecha_ini',
        'fecha_fin',
        'dias',
        'observaciones',
        'estado',
        'idvo',
        'idvr',
        'periodo',
        'fechadoc',
        'archivo',
        'nombredoc'
    ];
    public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
