<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsTeamMember extends Model
{
    protected $table = 'projects_team_members';
    
    public function projectTeam()
    {
        return $this->belongsTo(ProjectsTeam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}