<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cargo;

class Empleado extends Model
{
    protected $table = 'empleado';

    // Cambiamos la PK a 'id' auto-incremental
    protected $primaryKey = 'id';
    public $incrementing = true; // ahora es auto-increment
    public $timestamps = false;

    protected $fillable = [
        'nombresemp',
        'apellidosemp',
        'telefonemp',
        'direccionemp',
        'cargo_id'
    ];

    protected $with = ['cargoRelacion'];

  
    public function trabajos()
    {
        return $this->hasMany(Trabajo::class, 'empleado'); 
    }

   
    public function cargoRelacion()
{
    return $this->belongsTo(Cargo::class, 'cargo_id', 'id');
}

}
