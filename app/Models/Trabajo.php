<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;
    
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
        'empleado',
        'fechatrabajo',
        'estado',
        'montototal',           
        'montopagado',
        'nombretrb',
    ];
    
    protected $casts = [
        'fechatrabajo' => 'date',
    ];
    
    // Relaciones
    public function clienteRelacion()
    {
        return $this->belongsTo(Cliente::class, 'cliente', 'id');
    }
    
    public function responsable()
    {
        return $this->belongsTo(Responsable::class, 'responsable', 'id');
    }
    
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado', 'id');
    }
}