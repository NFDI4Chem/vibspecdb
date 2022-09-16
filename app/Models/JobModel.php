<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'team_id',
        'owner_id',
        'project_id',
        'study_id',
        'parameters',
        'out_data',
        'input_data',
    ];
}
