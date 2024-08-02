<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenEnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_enc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCliente');
            $table->boolean('estado');
            $table->dateTime('fechaPromesa');
            $table->timestamps();
            $table->foreign('idCliente')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_enc');
    }
}
