<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_compra');
            $table->unsignedBigInteger('id_producto');
            $table->string('sku_snapshot');
            $table->text('descripcion_snapshot')->nullable();
            $table->string('talla_snapshot', 50)->nullable();
            $table->integer('cantidad');
            $table->double('costo_unitario', 10, 2)->default(0); // GTQ
            $table->double('subtotal', 10, 2)->default(0);       // GTQ
            $table->timestamps();

            $table->foreign('id_compra', 'fk_compradet_compra')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('id_producto', 'fk_compradet_producto')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra_detalle');
    }
}
