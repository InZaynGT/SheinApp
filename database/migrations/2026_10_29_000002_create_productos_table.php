<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('talla', 50)->default('');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->double('costo_referencia', 10, 2)->nullable(); // GTQ
            $table->double('precio_sugerido', 10, 2)->nullable();  // GTQ
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->boolean('estado')->default(1); // 1 = activo, 0 = inactivo
            $table->timestamps();

            // Un SKU puede tener varias tallas, pero cada combinacion SKU + talla
            // solo puede existir una vez.
            $table->unique(['sku', 'talla'], 'uq_producto_sku_talla');
            $table->index('id_proveedor', 'idx_producto_proveedor');
            $table->foreign('id_proveedor', 'fk_producto_proveedor')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
