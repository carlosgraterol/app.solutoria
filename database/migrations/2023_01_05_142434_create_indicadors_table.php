<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicadorsTable extends Migration
{
    public function up()
    {
        Schema::create('indicadors', function (Blueprint $table) {
            $table->id();
            $table->string('nombreIndicador')->nullable();
            $table->string('codigoIndicador')->nullable();
            $table->string('unidadMedidaIndicador')->nullable();
            $table->decimal('valorIndicador', 10,2)->nullable();
            $table->date('fechaIndicador')->nullable();
            $table->string('tiempoIndicador')->nullable();
            $table->string('origenIndicador')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indicadors');
    }
}
