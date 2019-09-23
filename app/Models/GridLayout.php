<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GridLayout extends Model
{
    protected $fillable = ['key'];

    /**
     * Related grids
     */

    public function grid()
    {
        return $this->belongsTo('App\Models\Grid');
    }
}
