<?php

namespace App\Models;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProjectsTeam extends Model
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'projects_teams';
    protected $primaryKey = 'id';

    protected $fillable = [
        'project_id',
        'manager_id',
    ];

    public function project()
    {
        return $this->belongsTo(Client_projects::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'projects_team_members', 'team_id', 'student_id')->withTimestamps();
    }

}
