<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Tags\HasTags;
use Zoha\Metable;

use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Support\Facades\Storage;

class Study extends Model implements Auditable
{
    use HasFactory;
    use HasTags;
    use Metable;
    use \OwenIt\Auditing\Auditable;

    protected $metaTable = 'studies_meta';

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
        'study_photo_url',
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

    public function getPhotoUrl() {
        return ($this->image && $this->image->path) 
            ? Storage::disk(env('FILESYSTEM_DRIVER_PUBLIC', 'public'))
                ->temporaryUrl($this->image->path, now()->addMinutes(5)) 
            : null;
    }

    public function getTagsTranslated() {
        return ($this->tags) ? $this->tags->map(function ($tag) {
            return $tag->name;
        }) : [];
    }

    public function getMetadata() {
        return $this->getMetas();
    }

    public function getRequiredMeta() {
        $required_meta = config('meta.study_required');
        $rmeta = [];
        foreach ($required_meta as $rmkey) {
            if (!$this->hasMeta($rmkey)) {
                $rmeta[$rmkey] = '';
            } else {
                $rmeta[$rmkey] = $this->getMeta($rmkey);
            }
        }
        return $rmeta;
    }

    /*
    public function with_photo() {
        if ($this->image && $this->image->path) {
            $this->study_photo_path = Storage::disk('public')->url($this->image->path);
        }        
        return $this;
    }
    */

    public function with_tags_translated() {
        $this->tags_translated = $this->tags->map(function ($tag) {
            return $tag->name;
        });
        return $this;
    }

    public function with_metadata() {
        $this->metadata = $this->getMetas();
        return $this;
    }

    public function syncMeta($metas) {
        foreach ($this->getMetas() as $keydb => $valdb) {
            if (!isset($metas[$keydb])) {
                $this->deleteMeta($keydb);
            }
        }
        return $this->setMeta($metas);
    }

    /**
     * Get the user with the given email if belongs to the study
     *
     * @param  string  $email
     * @return bool
     */
    public function userWithEmail(string $email)
    {
        return $this->allUsers()->first(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Get the owner of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all of the study's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->project->users->merge($this->users);
    }

    /**
     * Determine if the given email address belongs to a user on the project.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }


    /**
     * Remove the given user from the study.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function removeUser($user)
    {
        $this->users()->detach($user);
    }


    /**
     * Get all of the users that belong to the study.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('studyMembership');
    }

    /**
     * Get the URL to the study's profile photo.
     *
     * @return string
     */
    public function getStudyPhotoUrlAttribute()
    {
        return $this->study_photo_path
            ? Storage::disk(env('FILESYSTEM_DRIVER_PUBLIC', 'public'))
                ->temporaryUrl($this->image->path, now()->addMinutes(5)) 
            : null;
    }
}
