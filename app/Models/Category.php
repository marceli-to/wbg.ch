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

    /**
     * Relation 'competence'
     */
    public function competence()
    {
        return $this->hasMany('App\Models\Competence');
    }
}
