<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'name',
        'principal',
        'description',
        'meta_description',
        'category_id',
        'client_id',
        'subcategory_id',
        'publish',
        'order'
    ];

    /**
     * Relation 'images'
     */
    public function images()
    {
        return $this->hasMany('App\Models\ProjectImage');
    }

    /**
     * Relation 'originalImages'
     */
    public function originalImages()
    {
        return $this->hasMany('App\Models\ProjectImage')->where('is_crop', '=', 0);
    }

    /**
     * Relation 'previewImages'
     */
    public function previewImages()
    {
        return $this->hasMany('App\Models\ProjectImage')->where('is_preview', '=', 1);
    }

    /**
     * Relation 'client'
     */
    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

    /**
     * Relation 'category'
     */
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    /**
     * Relation 'projects'
     */

    public function relations()
    {
        return $this->hasMany('App\Models\ProjectRelation', 'project_id', 'id');
    }

    /**
     * Get only published records
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopePublished($query)
    {
        return $query->where('publish', '=', '1');
    }
}
