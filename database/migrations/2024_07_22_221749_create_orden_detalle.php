<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idOrden');
            $table->string('SKU');
            $table->string('talla');
            $table->text('descripcion');
            $table->float('CostoMX',8,2);
            $table->float('CostoGT',8,2);
            $table->float('PrecioOfrecido',8,2);
            $table->foreign('idOrden')->references('id')->on('orden_enc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_detalle');
    }
}
