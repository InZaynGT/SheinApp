<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePagoEnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pago_enc', function (Blueprint $table) {
            $table->foreign('ID_CUENTA_BANCARIA')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
}
