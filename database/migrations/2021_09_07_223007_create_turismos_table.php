<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turismos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('foto');
            $table->integer('cupos')->default(0);

            
            $table->text('detalle')->nullable();
            $table->text('telefono')->nullable();
            $table->text('sitioweb')->nullable();
            $table->text('latitud')->nullable();
            $table->text('longitud')->nullable();

            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turismos');
    }
}
