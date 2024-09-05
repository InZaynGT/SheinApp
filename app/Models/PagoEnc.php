<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PagoDetModel;
use App\Models\FormaPago;
use App\Models\clienteModel;
use App\Models\cuentaBancariaModel;

class PagoEnc extends Model
{
    use HasFactory;
    protected $table = 'pago_enc';
    protected $fillable = ['id', 'idCliente', 'idPago', 'fecha', 'referencia', 'monto','NRO_DOCTO_BANCARIO','ID_CUENTA_BANCARIA', 'idPagoEnc', 'estado'];
    protected $primaryKey = 'id';
    
    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'idPago', 'id');
    }

    public function PagosDet()
    {
        return $this->hasMany(PagoDetModel::class, 'ID_CXC_PAGO', 'id');
    }

    public function Clientes()
    {
        return $this->belongsTo(clienteModel::class, 'idCliente', 'id');
    }
    
    public function CuentasBancarias()
    {
        return $this->belongsTo(cuentaBancariaModel::class, 'ID_CUENTA_BANCARIA', 'id');
    }

    public function Anticipo()
    {
        return $this->belongsTo(AnticipoModel::class, 'idPagoEnc', 'id');
    }
}
