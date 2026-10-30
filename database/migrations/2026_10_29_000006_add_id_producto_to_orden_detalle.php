<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdProductoToOrdenDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * Vincula cada linea de venta con el catalogo de productos.
     * Es nullable porque las lineas historicas no tienen producto asociado;
     * se llenan despues con el UPDATE de backfill por SKU + talla.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_detalle', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto')->nullable()->after('idOrden');

            // Si se borra un producto la venta NO se borra: solo pierde el vinculo.
            $table->foreign('id_producto', 'fk_ordendet_producto')
                  ->references('id')->on('productos')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_detalle', function (Blueprint $table) {
            $table->dropForeign('fk_ordendet_producto');
            $table->dropColumn('id_producto');
        });
    }
}
