<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeGridElement extends Model
{
    protected $fillable = [
        'grid_id',
        'project_image_id',
        'news_id',
        'position',
        'environment',
        'action'
    ];

    /**
     * ProjectImage relationship
     */

    public function projectimage()
    {
        return $this->hasOne('App\Models\ProjectImage', 'id', 'project_image_id');
    }

    /**
     * News relationship
     */

    public function news()
    {
        return $this->hasOne('App\Models\News', 'id', 'news_id');
    }

    /**
     * Related grids
     */

    public function grid()
    {
        return $this->belongsTo('App\Models\HomeGrid');
    }

    /**
     * Scope a query to only grids by a project.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeHighlight($query)
    {
        return $query->where('grid_id', '=', 1);
    } 

    /**
     * Get records which need to be deleted
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeToDelete($query)
    {
        return $query->where('action', '=', 'delete');
    }

    /**
     * Get records which need to be deleted
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeIsDevelopment($query)
    {
        return $query->where('environment', '=', 'development');
    }

    /**
     * Get records which need to be deleted
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeIsProduction($query)
    {
        return $query->where('environment', '=', 'production');
    }  
}
