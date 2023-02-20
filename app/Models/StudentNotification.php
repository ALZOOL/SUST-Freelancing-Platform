<?php

namespace App\Models;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StudentNotification extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'student_notifications';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'message_id',
    ];
}
