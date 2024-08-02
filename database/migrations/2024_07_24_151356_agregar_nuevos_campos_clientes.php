<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarNuevosCamposClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('nombre'); // Agrega una columna de tipo string, puede ser otro tipo según tus necesidades
            $table->text('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->integer('tipo_cli');
            $table->boolean('estado');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
