<?php

namespace App\Models;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    protected $table = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'avater',
        'role',
        'team_id',
        'password',
    ];
}
