<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Abono
 * 
 * @property int $id
 * @property int $id_pago
 * @property float $monto_abonado
 * @property float $numero_abono
 * @property Carbon $fecha_abono
 * 
 * @property Pago $pago
 *
 * @package App\Models
 */ 

class Abono extends Model
{
    protected $table = 'abono';
    
    protected $fillable = [
        'id_pago',
        'monto_abonado',
        'fecha_abono',
        'numero_abono'
    ];

    protected $casts = [
        'monto_abonado' => 'decimal:2',
        'fecha_abono' => 'datetime'
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago');
    }
}
