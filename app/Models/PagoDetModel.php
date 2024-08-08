<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PagoEnc;

class PagoDetModel extends Model
{
    use HasFactory;
    protected $table = 'pagodet';
    protected $fillable = ['ID_CXC', 'ID_CXC_PAGO', 'monto_aplicado'];
    protected $primaryKey = 'id';

    public function cxcDocumento()
    {
        return $this->belongsTo(CxcDocumentoModel::class, 'ID_CXC', 'id');
    }

    public function pagoEnc()
    {
        return $this->belongsTo(PagoEnc::class, 'ID_CXC_PAGO', 'id');
    }

    public function orden()
    {
        return $this->belongsTo(OrdenModel::class, 'ID_CXC', 'id');
    }
}
