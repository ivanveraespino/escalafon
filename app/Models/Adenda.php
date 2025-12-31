<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adenda extends Model
{
    use HasFactory;

    /**
     * Los campos que son asignables masivamente.
     *
     * @var array
     */
    protected $dateFormat = 'd/m/Y H:i:s'; // Formato típico en español para SQL Server

    protected $fillable = [
        'fecha_ini',
        'fecha_fin',
        'idtd',
        'nrodoc',
        'id_vinculo', 
        'archivo', 
    ];

    /**
     * Relación de uno a muchos: Un contrato tiene muchas adendas.
     */
    public function contrato()
    {
        return $this->belongsTo(Vinculo::class, 'id_vinculo');
    }
}
