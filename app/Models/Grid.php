<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grid extends Model
{
    protected $fillable = [
        'project_id',
        'layout_id',
        'order',
        'publish'
    ];

    /**
     * Get the layout associated with the grid.
     */

    public function layout()
    {
        return $this->hasOne('App\Models\GridLayout', 'id', 'layout_id');
    }

    /**
     * Get the images for the grid.
     */

    public function images()
    {
        return $this->hasMany('App\Models\ProjectImage');
    }

    /**
     * Get the images for the grid.
     */

    public function elements()
    {
        return $this->hasMany('App\Models\GridElement', 'grid_id', 'id');
    }

    /**
     * Scope a query to only grids by a project.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeByProject($query, $project_id)
    {
        return $query->where('project_id', '=', $project_id);
    }    
}
