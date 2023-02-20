<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSolvedTask extends Model
{
    use HasFactory;
    protected $table = 'student_solved_tasks';

    protected $fillable = [
        'task_id',
        'student_id',
        'solution',
        'report',
        'file_path',
        'points',
    ];
}
