<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'status',
        'shown',
        'user_id',
        'study_id',
        'argo_job_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function study()
    {
        return $this->belongsTo(Study::class);
    }

    public function argo_job()
    {
        return $this->belongsTo(ArgoJob::class);
    }
}
