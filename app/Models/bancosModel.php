<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\cuentaBancariaModel;

class bancosModel extends Model
{
    use HasFactory;
    protected $table = 'bancos'; // Asegúrate de que esta sea la tabla correcta
    protected $fillable = ['nombre'];
    public function CuentaBanc()
    {
        return $this->hasMany(cuentaBancariaModel::class, 'id_banco','id');
    }
}
