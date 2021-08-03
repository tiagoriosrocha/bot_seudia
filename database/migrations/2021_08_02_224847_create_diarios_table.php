<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chat_id',50);
            $table->date('dia');
            $table->integer('alimentacao')->nullable();
            $table->integer('sono')->nullable();
            $table->integer('filhos')->nullable();
            $table->integer('casal')->nullable();
            $table->integer('trabalho')->nullable();
            $table->integer('estudo')->nullable();
            $table->boolean('confirmado')->nullable();
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
        Schema::dropIfExists('diarios');
    }
}
