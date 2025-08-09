<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empleado
 * 
 * @property string $dui
 * @property string $nombresemp
 * @property string $apellidosemp
 * @property string|null $telefonemp
 * @property string|null $direccionemp
 * @property string|null $cargo
 * 
 * @property Collection|Trabajo[] $trabajos
 *
 * @package App\Models
 */
class Empleado extends Model
{
	protected $table = 'empleados';
	protected $primaryKey = 'dui';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'dui',
		'nombresemp',
		'apellidosemp',
		'telefonemp',
		'direccionemp',
		'cargo_id'
	];

	public function trabajos()
	{
		return $this->hasMany(Trabajo::class, 'empleado');
	}

	public function cargo()
{
    return $this->belongsTo(Cargo::class);
}
}
