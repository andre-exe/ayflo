<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Responsable
 * 
 * @property int $id
 * @property string $nombresresp
 * @property string $apellidosresp
 * @property string|null $telefonoresp
 * @property string|null $correoresp
 * @property int $cliente
 * 
 * @property Collection|Pago[] $pagos
 * @property Collection|Trabajo[] $trabajos
 * @property Collection|Bitacora[] $bitacoras
 *
 * @package App\Models
 */
class Responsable extends Model
{
	protected $table = 'responsable';
	public $timestamps = false;

	protected $casts = [
		'cliente' => 'int'
	];

	protected $fillable = [
		'nombresresp',
		'apellidosresp',
		'telefonoresp',
		'correoresp',
		'cliente'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'cliente');
	}

	public function pagos()
	{
		return $this->hasMany(Pago::class, 'id_responsable');
	}

	public function trabajos()
	{
		return $this->hasMany(Trabajo::class, 'responsable');
	}

	public function bitacoras()
	{
		return $this->hasMany(Bitacora::class, 'responsable');
	}
}
