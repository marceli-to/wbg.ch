<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeGridElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_grid_elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(0);
            $table->unsignedBigInteger('grid_id');
            $table->unsignedBigInteger('news_id')->nullable();
            $table->unsignedBigInteger('project_image_id')->nullable();
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
        Schema::dropIfExists('home_grid_elements');
    }
}
