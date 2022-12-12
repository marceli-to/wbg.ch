<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
	protected $fillable = [
    'email',
    'error',
    'processed',
  ];

  /**
   * The scope for unprocessed jobs.
   * 
   */
	public function scopeUnprocessed($query)
	{
		return $query->where('processed', 0);
	}

}
