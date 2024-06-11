<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelicula_cines', function (Blueprint $table) {
             $table->id();
            $table->integer('id_pelicula_sala'); 
            $table->date('fecha_publicacion'); 
            $table->date('fecha_fin'); 
            $table->unsignedBigInteger('id_pelicula'); 
            $table->unsignedBigInteger('id_sala'); 
            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_pelicula')->references('id_pelicula')->on('peliculas')->onDelete('cascade');
            $table->foreign('id_sala')->references('id_sala')->on('sala_cines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelicula_cines');
        
    }
};
