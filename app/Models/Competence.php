<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    protected $table = 'competences';

    protected $fillable = ['title', 'description', 'publish', 'order', 'category_id'];

    /**
     * Relation 'category'
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
