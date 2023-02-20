<?php

namespace App\Models;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProjectsTeam extends Model
{

    public function users()
    {
        //return $this->belongsToMany(Student::class);
        return $this->belongsToMany(Student::class, 'ProjectsTeamMember', 'team_id', 'student_id');
    }

    public function ProjectsTeamMember()
{
    return $this->belongsToMany(ProjectsTeamMember::class);
}

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'projects_teams';
    protected $primaryKey = 'team_id';

    protected $fillable = [
        'project_id',
        'manager_id',
    ];
}
