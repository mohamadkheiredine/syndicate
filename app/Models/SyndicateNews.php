<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateNews extends Model
{
    protected $table = 'syndicate_news';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'title',
        'what_type',
        'main_image',
        'short_description',
        'description',
        'status',
        'publish_status',
        'post_date',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // NewsController), only the real fields above. NOT NULL with no DB
    // default, so they still need a value on insert.
    protected $attributes = [
        'publish_title' => '',
        'publish_what_type' => '',
        'publish_main_image' => '',
        'tag' => '',
        'publish_tag' => '',
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

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('news/' . $value) : null;
    }

    // Old CMS ran rich-text through htmlspecialchars() before saving, so
    // existing rows have literally-escaped tags (e.g. "&lt;p&gt;") stored -
    // decode so the edit form's rich-text editor renders them instead of
    // showing the raw escaped text. A no-op on new, already-clean saves.
    public function getDescriptionAttribute($value)
    {
        return stripslashes(html_entity_decode($value ?? ''));
    }

    // Still used by the homepage teaser - safe to keep even though the new
    // CMS never populates publish_main_image, it just falls through to
    // main_image whenever that column is empty.
    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('news', $raw['main_image'], $raw['publish_main_image'], $raw['publish_status']);
    }
}
