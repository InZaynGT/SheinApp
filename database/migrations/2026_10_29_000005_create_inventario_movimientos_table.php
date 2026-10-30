<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_producto');
            $table->enum('tipo', ['entrada_compra', 'salida_orden', 'ajuste', 'devolucion']);
            $table->string('referencia_tipo', 50)->nullable(); // 'compra', 'orden_enc', 'ajuste'
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->integer('cantidad'); // positivo entra, negativo sale
            $table->integer('stock_resultante');
            $table->double('costo_unitario', 10, 2)->nullable(); // GTQ
            $table->dateTime('fecha');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_producto', 'fk_invmov_producto')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventario_movimientos');
    }
}
