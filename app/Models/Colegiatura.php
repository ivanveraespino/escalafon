<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Colegiatura extends Model 
{
    use HasFactory;

    protected $table = "colegiatura";
    protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; // Formato típico en español para SQL Server
    protected $fillable = [
        'id',
        'personal_id',        
        'nombre_colegio',        
        'nrodoc',
        'estado',
        'fechadoc',
        'idtd',
        'archivo',
        'nombredoc'
    ];
    public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}

