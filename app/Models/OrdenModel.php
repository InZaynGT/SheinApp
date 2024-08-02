<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenModel extends Model
{
    use HasFactory;
    protected $table = 'orden_enc'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['idCliente', 'fechaPromesa'];

    public function cliente()
    {
        return $this->belongsTo(clienteModel::class, 'idCliente', 'id');
    }

    public function CXC()
    {
        return $this->hasOne(CxcDocumentoModel::class, 'Nro_docto', 'id');
    }

    // OrdenModel
    public function detalleOrden()
    {
        return $this->hasMany(OrdenDetalleModel::class, 'idOrden');
    }
}
