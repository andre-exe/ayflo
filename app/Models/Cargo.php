<?php
// app/Models/Cargo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

     protected $table = 'cargo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'cargo', 'id');
        // hasMany(Modelo relacionado, clave foránea remota, clave primaria local)
    }

    // Scope para cargos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}