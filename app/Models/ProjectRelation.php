<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRelation extends Model
{
    protected $table = 'project_relations';

    protected $fillable = [
        'project_id',
        'related_project_id',
        'order',
    ];

    /**
     * Get the project of the relation.
     */
    public function related()
    {
        return $this->hasOne('App\Models\Project', 'id', 'related_project_id');
    }
    
    /**
     * Get the project of the relation.
     */
    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }  

}
