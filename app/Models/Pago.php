<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pago
 * 
 * @property int $id
 * @property int $id_cliente
 * @property int|null $id_responsable
 * @property float $montototal
 * @property float $abono
 * @property Carbon $fechaabono
 * 
 * @property Cliente $cliente
 * @property Responsable|null $responsable
 *
 * @package App\Models
 */
class Pago extends Model
{
	protected $table = 'pagos';
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_responsable' => 'int',
		'montototal' => 'float',
		'abono' => 'float',
		'fechaabono' => 'date'
	];

	protected $fillable = [
		'id_cliente',
		'id_responsable',
		'montototal',
		'abono',
		'fechaabono'
	];
	

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function responsable()
	{
		return $this->belongsTo(Responsable::class, 'id_responsable');
	}
}
