<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Trabajo
 * 
 * @property int $id
 * @property string|null $archivoescritura
 * @property string|null $archivoesquema
 * @property string|null $puntosrecorrido
 * @property string|null $archivodwg
 * @property string|null $archivokml
 * @property string|null $notas
 * @property string|null $insumos
 * @property int $cliente
 * @property int|null $responsable
 * @property Carbon|null $fechatrabajo
 * @property string|null $estado
 * @property string|null $empleado
 * @property float|null $montototal
 * @property float|null $montopagado
 * 
 * @property Collection|Bitacora[] $bitacoras
 *
 * @package App\Models
 */
class Trabajo extends Model
{
	protected $table = 'trabajos';
	public $timestamps = false;

	protected $casts = [
		'cliente' => 'int',
		'responsable' => 'int',
		'fechatrabajo' => 'datetime',
		'montototal' => 'float',
		'montopagado' => 'float'
	];

	protected $fillable = [
		'archivoescritura',
		'archivoesquema',
		'puntosrecorrido',
		'archivodwg',
		'archivokml',
		'notas',
		'insumos',
		'cliente',
		'responsable',
		'fechatrabajo',
		'estado',
		'empleado',
		'montototal',
		'montopagado'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'cliente');
	}

	public function responsable()
	{
		return $this->belongsTo(Responsable::class, 'responsable');
	}

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'empleado');
	}

	public function bitacoras()
	{
		return $this->hasMany(Bitacora::class, 'idtrabajo');
	}
}
