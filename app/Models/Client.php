<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    

    protected $table = 'clients';
    protected $primaryKey = 'client_id';


    protected $guarded=['client'] ;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_name',
        'company_email',
        'password',

    ];
}


