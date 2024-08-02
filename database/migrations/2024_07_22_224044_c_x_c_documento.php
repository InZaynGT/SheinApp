<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CXCDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CXCDocumento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Nro_docto');
            $table->unsignedBigInteger('idCliente');
            $table->date('fechaDocto');
            $table->float('montoDocto',8,2);
            $table->float('saldoDocto',8,2);
            $table->integer('nroPagos');
            $table->float('totalAcumuladoPagos',8,2);
            $table->boolean('estadoDocto');
            $table->timestamps();
            $table->foreign('idCliente')->references('id')->on('clientes');
            $table->foreign('Nro_docto')->references('id')->on('orden_enc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CXCDocumento');
    }
}
