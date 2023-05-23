<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    public function user()
    {
        return $this->belongsTo(Study::class, 'user_id');
    }
}
