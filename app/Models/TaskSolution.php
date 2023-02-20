<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSolution extends Model
{
    use HasFactory;

    protected $table = 'tasks_solutions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'task_id',
        'solution',
    ];
    
}
