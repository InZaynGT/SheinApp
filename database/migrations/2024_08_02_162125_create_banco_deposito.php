<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancoDeposito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banco_deposito', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_CUENTA_BANCARIA');
            $table->date('fecha');
            $table->string('nro_referencia');
            $table->float('debe');
            $table->float('haber');
            $table->text('notas')->nullable();
            $table->boolean('estado');
            $table->foreign('ID_CUENTA_BANCARIA')->references('id')->on('cuenta_bancaria');

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
        Schema::dropIfExists('banco_deposito');
    }
}
