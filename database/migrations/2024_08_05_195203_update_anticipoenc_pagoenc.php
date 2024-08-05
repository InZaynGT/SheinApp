<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAnticipoencPagoenc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anticipoenc', function (Blueprint $table) {
            $table->unsignedBigInteger('idPagoEnc')->nullable();
            
            $table->foreign('idPagoEnc')->references('id')->on('pago_enc');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anticipoenc', function (Blueprint $table) {
            $table->dropForeign(['idPagoEnc']);
            $table->dropColumn('idPagoEnc');
        });
    }
}
