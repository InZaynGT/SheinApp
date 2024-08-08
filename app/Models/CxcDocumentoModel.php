<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxcDocumentoModel extends Model
{
    use HasFactory;
    protected $table = 'cxcdocumento';
    protected $fillable = ['Nro_docto', 'idCliente', 'fechaDocto', 'montoDocto', 'saldoDocto', 'nroPagos', 'totalAcumuladoPagos', 'estadoDocto'];
    public function Orden()
    {
        return $this->belongsTo(OrdenModel::class, 'Nro_docto', 'id');
    }
    public function pagosAplicados()
    {
        return $this->hasMany(PagoDetModel::class, 'ID_CXC', 'id');
    }
    public function cliente()
    {
        return $this->belongsTo(clienteModel::class, 'idCliente','id');
    }
}
