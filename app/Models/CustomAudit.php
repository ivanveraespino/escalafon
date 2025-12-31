<?php

namespace App\Models;

use OwenIt\Auditing\Models\Audit;

class CustomAudit extends Audit
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    /**
     * Define el formato de fecha.
     */
    protected $dateFormat = 'd/m/Y H:i:s';

    public function fromDateTime($value)
    {
        return $value ? date($this->dateFormat, strtotime($value)) : null;
    }

    protected function asDateTime($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format($this->dateFormat);
    }
}
