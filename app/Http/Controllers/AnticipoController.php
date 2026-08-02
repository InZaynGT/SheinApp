<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnticipoModel;
use Illuminate\Support\Facades\DB;

class AnticipoController extends Controller
{
    /**
     * Obtiene el total de anticipos restantes de un cliente.
     */
    public function getAnticipos($idCliente)
    {
        $totalAnticipos = DB::table('anticipoenc')
            ->where('idCliente', $idCliente)
            ->sum('anticipoRestante');
        // Retornar los datos en formato JSON
        return response()->json($totalAnticipos);
    }

    /**
     * Obtiene la lista de anticipos restantes de un cliente.
     */
    public static function obtenerAnticiposRestantes($idCliente)
    {
        return AnticipoModel::where('idCliente', $idCliente)
            ->where('anticipoRestante', '>', 0)
            ->get();
    }
}
