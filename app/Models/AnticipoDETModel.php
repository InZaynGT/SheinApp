<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnticipoDETModel extends Model
{
    use HasFactory;
    protected $table = 'anticipodet'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['idCliente','formaPago','fecha','monto','aplicado','observaciones'];

    public function AnticipoENC()
    {
        return $this->belongsTo(AnticipoModel::class, 'idAnticipo','id');
    }

    public function orden()
    {
        return $this->belongsTo(OrdenModel::class, 'idOrden', 'id');
    }
}
