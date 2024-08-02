<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultEstadoOrdenENC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_enc', function (Blueprint $table) {
            $table->integer('estado')->default(1)->change(); // Cambia la columna `estado` para establecer el valor por defecto
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_enc', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
}
