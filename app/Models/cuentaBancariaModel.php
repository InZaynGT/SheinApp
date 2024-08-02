<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\bancosModel;
use App\Models\PagoEnc;

class cuentaBancariaModel extends Model
{
    use HasFactory;
    protected $table = 'cuenta_bancaria'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['numero_cuenta','nombre_cuenta','id_banco','estado'];
    
    public function Bancos()
    {
        return $this->belongsTo(bancosModel::class, 'id_banco', 'id');
    }

    public function pagoEnc()
    {
        return $this->hasMany(PagoEnc::class, 'ID_CUENTA_BANCARIA', 'id');
    }
}
