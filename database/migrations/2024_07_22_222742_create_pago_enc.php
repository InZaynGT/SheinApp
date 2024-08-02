<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoEnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_enc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCliente');
            $table->unsignedBigInteger('idPago');
            $table->date('fecha');
            $table->text('referencia');
            $table->float('monto',8,2);
            $table->timestamps();
            $table->foreign('idCliente')->references('id')->on('clientes');
            $table->foreign('idPago')->references('id')->on('_forma__pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago_enc');
    }
}
