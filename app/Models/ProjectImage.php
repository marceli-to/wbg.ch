<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    protected $table = 'project_images';

    protected $fillable = [
        'name',
        'caption',
        'order',
        'publish',
        'project_id'
    ];

    /**
     * Relation 'projects'
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }


    /**
     * Scope a query to show elements by project.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeNotInGrid($query)
    {
        return $query->where('is_grid', '=', 0);
    } 
}
