<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservacions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('fecha_inicio');
            $table->date('fecha_final')->nullable();
            $table->integer('cantidad_personas');
            $table->boolean('estado')->default(1);

            $table->unsignedBigInteger('turismo_id');
            $table->foreign('turismo_id')->references('id')->on('turismos');

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
        Schema::dropIfExists('reservacions');
    }
}
