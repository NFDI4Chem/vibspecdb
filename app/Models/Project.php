<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use Spatie\Tags\HasTags;
use Zoha\Metable;

class Project extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use HasTags;
    use Metable;

    protected $metaTable = 'projects_meta';

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
        'project_photo_path'
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

    public function studies()
    {
        return $this->hasMany(Study::class, 'project_id');
    }

    protected function getPublicUrlAttribute()
    {
        // return  env('APP_URL', null)."/projects/".urlencode($this->slug);
    }

    protected function getPrivateUrlAttribute()
    {
        // return  env('APP_URL', null)."/projects/".urlencode($this->url);
    }

    /**
     * Authors that belongs to project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function authors()
    {
        return $this->belongsToMany(Author::class)
            ->withPivot('contributor_type', 'sort_order');
    }

    /**
     * Remove the given user from the project.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function removeUser($user)
    {
        $this->users()->detach($user);
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
     * Get all of the project's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->users;
    }

    /**
     * Get all of the users that belong to the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('projectMembership');
    }

    /**
     * Determine if the given user belongs to the project.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsProject($this);
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
     * Get the user with the given email if belongs to the project
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
     * Get the user project role
     *
     * @param  string  $email
     * @return bool
     */
    public function userProjectRole(string $email)
    {
        $user = $this->userWithEmail($email);

        if ($user) {
            if ($user['projectMembership']) {
                return $user['projectMembership']['role'];
            } elseif ($this->owner_id == $user->id) {
                return 'owner';
            }
        }
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
        $required_meta = config('meta.project_required');
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

}
