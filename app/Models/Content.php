<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Content extends Model
{
    use HasTranslations;
    
    protected $table = 'content';

    protected $fillable = [
      'key',
      'title',
      'text',
      'publish',
    ];

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
