<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProjectRequest extends Model
{
    use HasFactory;


    protected $table = 'client_project_requests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'project_title',
        'project_description',
        'project_file_path',
    ];
}
