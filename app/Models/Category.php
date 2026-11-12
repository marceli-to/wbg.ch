<?php
namespace App\Models;

use App\Services\MenuService;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The navigation menu is cached; rebuild it whenever this model changes.
     */
    protected static function booted(): void
    {
        static::saved(fn () => MenuService::flush());
        static::deleted(fn () => MenuService::flush());
    }

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'order',
        'publish'
    ];

    /** Fixed subcategories of the "Panoptikum" category. */
    public const SUBCATEGORIES = [
        1 => 'Archiv',
        2 => 'Digital',
        3 => 'Wettbewerb',
    ];

    public const PANOPTIKUM_ID = 3;

    public $subcategories = self::SUBCATEGORIES;

    public $panoptikumId = self::PANOPTIKUM_ID;

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
        return $query->where($query->qualifyColumn('publish'), '=', 1);
    }
}
