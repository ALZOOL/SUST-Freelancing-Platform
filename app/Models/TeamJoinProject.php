<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamJoinProject extends Model
{
    use HasFactory;


    protected $table = 'team_join_projects';
    protected $primaryKey = 'id';

    protected $fillable = [
        'project_id',
        'project_title',
        'team_id'
    ];
}