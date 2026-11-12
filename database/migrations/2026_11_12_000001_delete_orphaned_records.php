<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Remove records the old admin left behind when deleting projects or grid
 * rows (checked against the production dump of 2026-11-12):
 *
 * - grids of deleted projects (93) and their elements
 * - grid elements whose grid no longer exists (26); MySQL may reuse grid ids
 *   after a restart, so a new row could "inherit" stale elements
 * - grid elements pointing to deleted images or news
 * - images of deleted projects (30 rows; files are left on disk)
 * - project relations to/from deleted projects (1)
 * - client links to deleted projects (3) are cleared
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $projects = DB::table('projects')->select('id');

            DB::table('grids')->whereNotIn('project_id', $projects)->delete();

            DB::table('grid_elements')
                ->whereNotIn('grid_id', DB::table('grids')->select('id'))
                ->orWhere(fn ($q) => $q->whereNotNull('project_image_id')->whereNotIn('project_image_id', DB::table('project_images')->select('id')))
                ->orWhere(fn ($q) => $q->whereNotNull('news_id')->whereNotIn('news_id', DB::table('news')->select('id')))
                ->delete();

            DB::table('home_grid_elements')
                ->whereNotIn('grid_id', DB::table('home_grids')->select('id'))
                ->orWhere(fn ($q) => $q->whereNotNull('project_image_id')->whereNotIn('project_image_id', DB::table('project_images')->select('id')))
                ->orWhere(fn ($q) => $q->whereNotNull('news_id')->whereNotIn('news_id', DB::table('news')->select('id')))
                ->delete();

            DB::table('project_images')->whereNotIn('project_id', $projects)->delete();

            DB::table('project_relations')
                ->whereNotIn('project_id', $projects)
                ->orWhereNotIn('related_project_id', $projects)
                ->delete();

            DB::table('clients')
                ->whereNotNull('project_id')
                ->where('project_id', '<>', 0)
                ->whereNotIn('project_id', $projects)
                ->update(['project_id' => null]);
        });
    }

    public function down(): void
    {
        // Data cleanup, nothing to restore
    }
};
