<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTeam extends Model
{
    protected $table = 'student_teams';
    
    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }
    
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
}