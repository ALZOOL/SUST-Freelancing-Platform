<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InterviewRequest extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'interview_requests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'role',
        'current_rank',
        'next_rank'
    ];
}
