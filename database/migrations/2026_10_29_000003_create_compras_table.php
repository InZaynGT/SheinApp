<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proveedor');
            $table->date('fecha');
            $table->string('nro_factura')->nullable();
            $table->double('subtotal', 10, 2)->default(0);  // GTQ
            $table->double('impuestos', 10, 2)->default(0); // GTQ
            $table->double('flete', 10, 2)->default(0);     // GTQ
            $table->double('total', 10, 2)->default(0);     // GTQ
            $table->tinyInteger('estado')->default(1); // 1 = borrador, 2 = recibida, 3 = cancelada
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_proveedor', 'fk_compra_proveedor')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
}
