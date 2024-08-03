<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnticipoDET extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anticipoDET', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idAnticipo');
            $table->unsignedBigInteger('idOrden');
            $table->float('montoAplicado');
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
        Schema::dropIfExists('anticipoDET');
    }
}
