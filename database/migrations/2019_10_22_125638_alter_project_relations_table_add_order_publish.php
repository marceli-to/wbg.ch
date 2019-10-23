<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectRelationsTableAddOrderPublish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_relations', function($table) {
            $table->integer('publish')->default(0)->after('related_project_id');
            $table->integer('order')->default(-1)->after('related_project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_relations', function($table) {
            $table->dropColumn('publish');
            $table->dropColumn('order');
        });
    }
}
