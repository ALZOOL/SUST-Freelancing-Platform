<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client_projects extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function projectsTeam()
{
    return $this->hasOne(ProjectsTeam::class);
}

// ProjectsTeam model
public function clientProject()
{
    return $this->belongsTo(ClientProject::class);
}

    protected $table = 'client_projects';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'title',
        'category',
        'description',
        'deadline',
        'status',
        'rank',
        'team_id',
        'frontend',
        'backend',
        'ui_ux',
        'security',
        'team_count'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       
    ];
}
