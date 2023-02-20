<?php

namespace App\Models;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DefaultRank extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    protected $table = 'student_ranks';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'rank',
        'points',
    ];
}
