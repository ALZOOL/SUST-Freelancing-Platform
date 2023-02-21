<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    use HasFactory;



    protected $table = 'stars';
    protected $primaryKey = 'id';

    protected $fillable = [
        'project_id',
        'client_id',
        'team_id',
        'student_id',
    ];
}
