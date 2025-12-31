<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regimen extends Model
{
    use HasFactory;

    protected $table = "regimen";
    protected $primaryKey = 'id';
    protected $dateFormat = 'd/m/Y H:i:s'; 

    protected $fillable = [
        'id',
        'nombre'
    ];
    
}
