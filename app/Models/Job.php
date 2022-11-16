<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
  protected $fillable = [
    'title',
    'text',
    'order',
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
