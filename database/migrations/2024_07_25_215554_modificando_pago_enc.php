<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificandoPagoEnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('pago_enc', function (Blueprint $table) {
            $table->string('NRO_DOCTO_BANCARIO')->nullable(); // Ajusta 'some_existing_column' según la estructura de tu tabla
            $table->unsignedBigInteger('ID_CUENTA_BANCARIA')->nullable();
            $table->string('referencia')->nullable()->change();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pago_enc', function (Blueprint $table) {
            $table->dropColumn('NRO_DOCTO_BANCARIO');
            $table->dropColumn('ID_CUENTA_BANCARIA');
            $table->string('referencia')->nullable(false)->change();
        });
    }
}
