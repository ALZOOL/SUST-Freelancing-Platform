<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentJoinProject extends Model
{
    use HasFactory;


    protected $table = 'student_join_projects';
    protected $primaryKey = 'id';

    protected $fillable = [
        'project_id',
        'project_title',
        'client_id',
        'client_email',
        'student_id',
        'student_name',
        'student_role',
    ];
}
