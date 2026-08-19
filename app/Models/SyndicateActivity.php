<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateActivity extends Model
{
    protected $table = 'syndicate_activities';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'title',
        'post_date',
        'place',
        'main_image',
        'any_file',
        'short_description',
        'description',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns and dead fields - the new CMS never writes to
    // these (see SyndicateActivityController), only the real fields above.
    // NOT NULL with no DB default, so they still need a value on insert.
    protected $attributes = [
        'what_type' => '',
        'publish_title' => '',
        'publish_post_date' => '1970-01-01',
        'publish_place' => '',
        'publish_main_image' => '',
        'other_image' => '',
        'publish_other_image' => '',
        'publish_any_file' => '',
        'publish_short_description' => '',
        'publish_description' => '',
    ];

    protected $casts = [
        'post_date' => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function gallery()
    {
        return $this->hasMany(SyndicateActivityGallery::class, 'activities_id');
    }

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('activities/' . $value) : null;
    }

    public function getAnyFileAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('activities-files/' . $value) : null;
    }

    // Old CMS ran rich-text through htmlspecialchars() before saving, so
    // existing rows have literally-escaped tags (e.g. "&lt;p&gt;") stored -
    // decode so the edit form's rich-text editor renders them instead of
    // showing the raw escaped text. A no-op on new, already-clean saves.
    public function getDescriptionAttribute($value)
    {
        return stripslashes(html_entity_decode($value ?? ''));
    }

    // Same double-decode as description above - the old CMS ran this
    // through htmlspecialchars() too.
    public function getShortDescriptionAttribute($value)
    {
        return stripslashes(html_entity_decode($value ?? ''));
    }
}
