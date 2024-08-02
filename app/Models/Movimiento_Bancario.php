<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento_Bancario extends Model
{
    use HasFactory;
    protected $table = 'banco_deposito'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['ID_CUENTA_BANCARIA','fecha','nro_referencia','debe','haber','notas','estado'];

    public function CuentaBancaria()
    {
        return $this->belongsTo(cuentaBancariaModel::class, 'ID_CUENTA_BANCARIA', 'id');
    }
}
