<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The old admin deleted grid rows without their elements. MySQL 5.7 reuses
 * auto increment ids after a restart, so a newly created row could "inherit"
 * those stale elements (e.g. images of another project). Remove them.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('grid_elements')
            ->whereNotIn('grid_id', DB::table('grids')->select('id'))
            ->delete();

        DB::table('home_grid_elements')
            ->whereNotIn('grid_id', DB::table('home_grids')->select('id'))
            ->delete();
    }

    public function down(): void
    {
        // Data cleanup, nothing to restore
    }
};
