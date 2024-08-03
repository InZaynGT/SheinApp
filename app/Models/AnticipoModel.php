<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnticipoModel extends Model
{
    use HasFactory;
    protected $table = 'anticipoenc'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['idCliente','formaPago','fecha','monto','aplicado','observaciones'];

    public function Cliente()
    {
        return $this->belongsTo(clienteModel::class, 'idCliente','id');
    }
}
