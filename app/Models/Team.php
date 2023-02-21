<?php

namespace App\Models;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Team extends Model
{

    public function users()
    {
        //return $this->belongsToMany(Student::class);
        return $this->belongsToMany(Student::class, 'student_teams', 'team_id', 'student_id');
    }

    public function studentTeams()
{
    return $this->belongsToMany(StudentTeam::class);
}

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'teams';
    protected $primaryKey = 'team_id';

    protected $fillable = [
        'team_name',
        'invitation_link',
        'team_leader'
    ];
}
