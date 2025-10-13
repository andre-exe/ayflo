<?php
/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Pago
 * 
 * @property int $id
 * @property int $id_cliente
 * @property int|null $id_responsable
 * @property int $id_trabajo
 * @property float $monto_total
 * @property float $monto_pendiente
 * @property Carbon $fecha_creacion
 * 
 * @property Trabajo $trabajo
 *
 * @package App\Models
 */

class Pago extends Model
{
    protected $table = 'pago';
    
    protected $fillable = [
        'id_cliente',
        'id_responsable',
        'id_trabajo',
        'monto_total',
        'monto_pendiente',
        'estado',
        'fecha_creacion'
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'monto_pendiente' => 'decimal:2',
        'fecha_creacion' => 'date'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function responsable()
    {
        return $this->belongsTo(Responsable::class, 'id_responsable');
    }

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'id_trabajo');
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'id_pago');
    }

    public function getPorcentajePagadoAttribute()
    {
        if ($this->monto_total > 0) {
            return (($this->monto_total - $this->monto_pendiente) / $this->monto_total) * 100;
        }
        return 0;
    } 
}
