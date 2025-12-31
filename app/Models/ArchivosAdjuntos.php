<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivosAdjuntos extends Model
{
    use HasFactory;

    protected $table = "archivos_adjuntos";
    protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

    protected $fillable = [
        'id',
        'personal_id',
        'idtd',        
        'archivo',
    ];
}
