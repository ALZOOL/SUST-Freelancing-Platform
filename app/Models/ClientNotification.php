<?php

namespace App\Models;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ClientNotification extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'client_notifications';
    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'message_id',
    ];
}
