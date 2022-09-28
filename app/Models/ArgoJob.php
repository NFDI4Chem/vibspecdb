<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArgoJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'errors',
        'submit_uid',
        'argo_uid',
        'type',
        'name',
        'description',
        'team_id',
        'owner_id',
        'project_id',
        'study_id',
        'finishedAt'
    ];
}
