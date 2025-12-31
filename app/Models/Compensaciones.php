<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Compensaciones extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = "compensaciones";
    protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; // Formato típico en español para SQL Server
    protected $fillable = [
        'id',
        'personal_id',
        'tipo_compensacion',        
        'descripcion',
        'idtd',
        'nrodoc',
        'fecha_ini',
        'fecha_fin',
        'fecha_documento',
        'archivo',
        'dias',
    ];
    public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}

