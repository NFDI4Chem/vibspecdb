<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team'
    ];

    protected $appends = [
        'profile_photo_url',
        'photo_url',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];
    /**
     * Get the default team profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function getProfilePhotoUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the URL to the  profile photo.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? Storage::disk(env('FILESYSTEM_DRIVER_PUBLIC', 'public'))
                ->temporaryUrl($this->image->path, now()->addMinutes(36000)) 
            : null;
    }
}
