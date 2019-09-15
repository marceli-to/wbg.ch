<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetenceMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competence_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('caption', 255)->nullable();
            $table->string('filetype', 3)->nullable();
            $table->integer('publish')->default(0);
            $table->integer('order')->default(-1);
            $table->unsignedBigInteger('competence_id')->nullable();
            $table->foreign('competence_id')->references('id')->on('competences');
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
        Schema::dropIfExists('competence_media');
    }
}
