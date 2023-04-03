<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSystemObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'relative_url',
        'path',
        'type',
        'key',
        'compressionInfo',
        'kernelSessionInfo',
        'color',
        'starred',
        'is_public',
        'is_deleted',
        'is_archived',
        'is_original',
        'is_verified',
        'is_processed',
        'is_root',
        'sort_order',
        'owner_id',
        'project_id',
        'study_id',
        'version_id',
        'version',
        'parent_id',
        'settings',
        'info',
        'level',
        'has_children',
        'uppyid',
        'size',
        'ftype',
    ];

    // Recursive children
    public function children()
    {
        return $this->hasMany(FileSystemObject::class, 'parent_id', 'id')->with('children');
    }

    // Recursive parents
    public function parents() {
        return $this->belongsTo(FileSystemObject::class, 'parent_id')->with('parent');
    }

    public function parent()
    {
        return $this->belongsTo(FileSystemObject::class, 'parent_id');
    }

    // One level child
    public function child()
    {
        return $this->hasMany(FileSystemObject::class, 'parent_id');
    }

    /*
    public function getPathAttribute()
    {
        $path = [];
        if ($this->parent_id) {
            $parent = $this->parent;
            $parent_path = $parent->path;
            $path = array_merge($path, $parent_path);
        }
        $path[] = $this->name;
        return $path;
    }
    */

    public function jobs()
    {
        return $this->belongsToMany(ArgoJob::class, 'argo_jobs_files', 'file_id', 'job_id')
            ->as('jobs');
    }

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
