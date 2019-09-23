<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectsTableAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('principal', 255);
            $table->text('description_short')->nullable();
            $table->text('description')->nullable();
            $table->integer('publish')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('principal');
            $table->dropColumn('description_short');
            $table->dropColumn('description');
            $table->dropColumn('publish');
            $table->dropColumn('category_id');
            $table->dropColumn('client_id');
        });
    }
}
