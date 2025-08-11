<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Egreso
 * 
 * @property int $id
 * @property float $montoegreso
 * @property string|null $descripcionegreso
 * @property Carbon $fecha
 *
 * @package App\Models
 */
class Egreso extends Model
{
	protected $table = 'egresos';
	public $timestamps = false;

	protected $casts = [
		'montoegreso' => 'float',
		'fecha' => 'date'
	];

	protected $fillable = [
		'montoegreso',
		'descripcionegreso',
		'fecha'
	];
}
