<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeamTableMakeRoleNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team', function($table) {
            $table->dropColumn('role');
        });
    
        Schema::table('team', function (Blueprint $table) {
            $table->string('role', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team', function($table) {
            $table->dropColumn('role');
        });
    }
}
