<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Support\Facades\Storage;

class Study extends Model implements Auditable
{
    use HasFactory;
    use HasTags;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'starred',
        'location',
        'is_public',
        'url',
        'description',
        'type',
        'uuid',
        'access',
        'access_type',
        'team_id',
        'owner_id',
        'project_id',
        'study_photo_path'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'public_url',
        'private_url',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function assays()
    {
        return $this->hasMany(Assay::class, 'study_id');
    }
    
    public function image()
    {
        return $this->hasOne(Image::class, 'study_id');
    }

    protected function getPublicUrlAttribute()
    {
        return  env('APP_URL', null)."/studies/".urlencode($this->slug);
    }

    protected function getPrivateUrlAttribute()
    {
        return  env('APP_URL', null)."/studies/".urlencode($this->url);
    }

    public function with_photo() {
        if ($this->image && $this->image->path) {
            $this->study_photo_path = Storage::disk('public')->url($this->image->path);
        }        
        return $this;
    }

    public function with_tags_translated() {
        $this->tags_translated = $this->tags->map(function ($tag) {
            return $tag->name;
        });
        return $this;
    }
}
