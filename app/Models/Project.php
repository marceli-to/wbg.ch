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
}
