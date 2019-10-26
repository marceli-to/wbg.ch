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
        'url',
        'project_id',
        'is_grid',
        'is_preview',
        'is_crop'
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

    /**
     * Scope a query to show elements by project.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeWithCrop($query, $parent_id)
    {
        return $query->where('parent_id', '=', $parent_id);
    } 
}
