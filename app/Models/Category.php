<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'order',
        'publish'
    ];

    public $subcategories = [
        1 => 'Archiv',
        2 => 'Digital',
        3 => 'Wettbewerb'
    ];

    public $panoptikumId = 3;

    /**
     * Relation 'competence'
     */
    public function competence()
    {
        return $this->hasMany('App\Models\Competence');
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
