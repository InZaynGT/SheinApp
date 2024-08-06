<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnticipoModel extends Model
{
    use HasFactory;
    protected $table = 'anticipoenc'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['idCliente','formaPago','fecha','monto','aplicado','observaciones', 'anticipoRestante', 'idPagoEnc'];

    public function Cliente()
    {
        return $this->belongsTo(clienteModel::class, 'idCliente','id');
    }
    
    public function formaPago(){
        return $this->belongsTo(FormaPago::class, 'formaPago','id');
    }

    public function PagoENC()
    {
        return $this->belongsTo(PagoEnc::class, 'idPagoEnc', 'id');
    }
}
