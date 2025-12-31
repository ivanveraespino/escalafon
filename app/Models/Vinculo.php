<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vinculo
 *
 * @property int $id
 * @property int|null $personal_id
 * @property string|null $id_unidad_organica
 * @property string|null $id_unidad_organica
 * @property string|null $obras_pro
 * @property string|null $id_motivo_fin_vinculo
 * @property int|null $id_regimen
 * @property int|null $archivo
 * @property int|null $archivo_cese
 * @property int|null $id_condicion_laboral
 * @property Carbon|null $fecha_ini
 * @property Carbon|null $fecha_fin
 * @property int|null $id_tipo_documento
 * @property int|null $id_tipo_documento_fin
 * @property string|null $nro_doc
 * @property string|null $nro_doc_fin
 * @property int|null $id_accion_vin
 * @property Carbon|null $fecha_doc
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Personal|null $personal
 *
 * @package App\Models
 */
class Vinculo extends Model
{
	protected $table = 'vinculos';
    protected $dateFormat = 'd/m/Y H:i:s';

	protected $casts = [
		'personal_id' => 'int',
		'id_regimen' => 'int',
		'archivo' => 'int',
		'archivo_cese' => 'int',
		'fecha_ini' => 'datetime',
		'fecha_fin' => 'datetime',
		'id_tipo_documento' => 'int',
		'id_tipo_documento_fin' => 'int',
		'id_accion_vin' => 'int',
		'fecha_doc' => 'datetime'

	];

	protected $fillable = [
		'personal_id',
		'id_unidad_organica',
		'id_cargo',
		'id_depens',
		'obras_pro',
		'id_motivo_fin_vinculo',
		'id_regimen',
		'id_condicion_laboral',
		'fecha_ini',
		'fecha_fin',
		'id_tipo_documento',
		'id_tipo_documento_fin',
		'nro_doc',
		'nro_doc_fin',
		'archivo',
		'archivo_cese',
		'id_accion_vin',
		'fecha_doc',
		'denominacion',
		'motivocese',
		'filea',
		'lomo',
        'nombredocvin',
        'nombredoccese'
	];

	public function personal()
	{
		return $this->belongsTo(Personal::class, 'personal_id');
	}
}
