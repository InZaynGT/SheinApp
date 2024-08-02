<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDetalleModel extends Model
{
    use HasFactory;
    protected $table = 'orden_detalle';
    protected $fillable = ['idOrden', 'SKU', 'talla', 'descripcion', 'CostoMX', 'CostoGT', 'PrecioOfrecido'];

    // OrdenDetalleModel
    public function orden()
    {
        return $this->belongsTo(OrdenModel::class, 'idOrden');
    }
}
