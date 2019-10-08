<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeGrid extends Model
{
    protected $fillable = ['layout_id', 'order', 'publish'];

    /**
     * Get the layout associated with the row.
     */

    public function layout()
    {
        return $this->hasOne('App\Models\HomeGridLayout', 'id', 'layout_id');
    }

    /**
     * Get the images for the grid.
     */

    public function images()
    {
        return $this->hasMany('App\Models\ProjectImage');
    }

    /**
     * Get the images for the grid.
     */

    public function elements()
    {
        return $this->hasMany('App\Models\HomeGridElement', 'grid_id', 'id');
    }
  
}
