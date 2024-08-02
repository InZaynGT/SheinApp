<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clienteModel extends Model
{
    use HasFactory;
    protected $table = 'clientes'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['nombre', 'direccion', 'telefono', 'tipo_cli'];
    public function ordenes()
    {
        return $this->hasMany(OrdenModel::class, 'idCliente','id');
    }

    public function PagoEnc()
    {
        return $this->hasMany(PagoEnc::class, 'idCliente','id');
    }

    public function documentos(){
        return $this->hasMany(CxcDocumentoModel::class, 'idCliente','id');
    }
}
