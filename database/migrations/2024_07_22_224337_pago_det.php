<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagoDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PagoDet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_CXC_PAGO');
            $table->unsignedBigInteger('ID_CXC');
            $table->float('monto_aplicado',8,2);
            $table->timestamps();
            $table->foreign('ID_CXC_PAGO')->references('id')->on('pago_enc');
            $table->foreign('ID_CXC')->references('id')->on('CXCDocumento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PagoDet');
    }
}
