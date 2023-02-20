<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRank extends Model
{
    use HasFactory;
    protected $table = 'student_ranks';
    protected $primaryKey = 'student_id';


    protected $fillable = [
        'rank',
        'points',
    ];
}
