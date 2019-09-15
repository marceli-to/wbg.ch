<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'team';

    protected $fillable = ['name', 'firstname', 'role',  'phone', 'email', 'media', 'order', 'publish'];

}
