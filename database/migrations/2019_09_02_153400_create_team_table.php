<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('firstname', 50);
            $table->json('role')->nullable();
            $table->json('position')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 50);
            $table->json('cv')->nullable();
            $table->string('media', 255)->nullable();
            $table->integer('order')->default(-1);
            $table->integer('publish')->default(0);
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
        Schema::dropIfExists('team');
    }
}
