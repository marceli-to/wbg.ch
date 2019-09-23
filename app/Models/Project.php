<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'name',
        'principal',
        'description_short',
        'description',
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
