<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Task extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'level',
        'category',
        'description',
        'level',
        'rank',
        'file_path',
        'points',
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
