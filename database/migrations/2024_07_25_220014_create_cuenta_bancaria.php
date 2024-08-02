<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentaBancaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_bancaria', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cuenta');
            $table->string('nombre_cuenta');
            $table->unsignedBigInteger('id_banco');
            $table->boolean('estado');
            $table->foreign('id_banco')->references('id')->on('bancos');
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
        Schema::dropIfExists('cuenta_bancaria');
    }
}
