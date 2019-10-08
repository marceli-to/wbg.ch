<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHomeGridElementsTableAddStaging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_grid_elements', function($table) {
            $table->enum('environment', ['production', 'development'])->after('project_image_id');
            $table->enum('action', ['keep', 'delete'])->after('project_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_grid_elements', function($table) {
            $table->dropColumn('environment');
            $table->dropColumn('action');
        });
    }
}
