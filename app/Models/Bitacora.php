<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bitacora
 * 
 * @property int $id
 * @property int $cliente
 * @property int|null $responsable
 * @property float $monto
 * @property int $idtrabajo
 * @property Carbon $fechatrabajobitacora
 * @property string|null $descripcionbitacora
 * 
 * @property Trabajo $trabajo
 *
 * @package App\Models
 */
class Bitacora extends Model
{
	protected $table = 'bitacora';
	public $timestamps = false;

	protected $casts = [
		'cliente' => 'int',
		'responsable' => 'int',
		'monto' => 'float',
		'idtrabajo' => 'int',
		'fechatrabajobitacora' => 'datetime'
	];

	protected $fillable = [
		'cliente',
		'responsable',
		'monto',
		'idtrabajo',
		'fechatrabajobitacora',
		'descripcionbitacora'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'cliente');
	}

	public function responsable()
	{
		return $this->belongsTo(Responsable::class, 'responsable');
	}

	public function trabajo()
	{
		return $this->belongsTo(Trabajo::class, 'idtrabajo');
	}
}
