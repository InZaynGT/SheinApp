<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    use HasFactory;
    protected $table = '_forma__pago';
    protected $fiLlable = ['id','nombre'];

    public function pagosEnc()
    {
        return $this->hasMany(PagoEnc::class, 'idPago','id');
    }
    
    public function Anticipo(){
        return $this->hasMany(AnticipoModel::class, 'formaPago','id');
    }
}
