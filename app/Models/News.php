<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'text',
        'link',
        'linkInternal',
        'linkText',
        'linkNewWindow',
    ];

    /**
     * Competence relationship
     */

    public function competence()
    {
        return $this->hasOne('App\Models\Competence', 'id', 'linkInternal');
    }
}
