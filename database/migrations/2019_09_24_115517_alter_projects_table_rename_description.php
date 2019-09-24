<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectsTableRenameDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function($table) {
            $table->dropColumn('description');
            $table->dropColumn('description_short');
        });

        Schema::table('projects', function($table) {
            $table->text('description')->nullable()->after('principal');
            $table->text('meta_description')->nullable()->after('principal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function($table) {
            $table->dropColumn('description');
            $table->dropColumn('meta_description');
        });
    }
}
