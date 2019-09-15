<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceMedia extends Model
{
    protected $table = 'competence_media';

    protected $fillable = ['name', 'caption', 'filetype', 'publish', 'order', 'competence_id'];

}
