<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnticipoEnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anticipoENC', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCliente');
            $table->unsignedBigInteger('formaPago');
            $table->date('fecha');
            $table->float('monto');
            $table->float('aplicado');
            $table->text('observaciones');

            $table->foreign('idCliente')->references('id')->on('clientes');
            $table->foreign('formaPago')->references('id')->on('_forma__pago');

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
        Schema::dropIfExists('anticipoENC');
    }
}
