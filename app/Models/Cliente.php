<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 * 
 * @property int $id
 * @property string $nombrescliente
 * @property string $apellidoscliente
 * @property string|null $telefonocliente
 * @property string|null $correocliente
 * 
 * @property Collection|Pago[] $pagos
 * @property Collection|Responsable[] $responsables
 * @property Collection|Trabajo[] $trabajos
 * @property Collection|Bitacora[] $bitacoras
 *
 * @package App\Models
 */
class Cliente extends Model
{
	protected $table = 'cliente';
	
	public $timestamps = false;

	protected $fillable = [
		'nombrescliente',
		'apellidoscliente',
		'telefonocliente',
		'correocliente'
	];

	public function pagos()
	{
		return $this->hasMany(Pago::class, 'id_cliente');
	}

	public function responsables()
	{
		return $this->hasMany(Responsable::class, 'cliente');
	}

	public function trabajos()
	{
		return $this->hasMany(Trabajo::class, 'cliente');
	}

	public function bitacoras()
	{
		return $this->hasMany(Bitacora::class, 'cliente');
	}
}
