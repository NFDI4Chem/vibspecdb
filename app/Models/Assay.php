<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Assay extends Model implements Auditable
{
    use HasFactory;
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
        'study_id',
        'assay_photo_path'
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

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    protected function getPublicUrlAttribute()
    {
        return  env('APP_URL', null)."/assays/".urlencode($this->slug);
    }

    protected function getPrivateUrlAttribute()
    {
        return  env('APP_URL', null)."/assays/".urlencode($this->url);
    }
}
