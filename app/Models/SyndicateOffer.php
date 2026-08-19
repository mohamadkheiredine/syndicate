<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateOffer extends Model
{
    protected $table = 'syndicate_offers';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'title',
        'main_image',
        'new_image',
        'pdf',
        'start_date',
        'end_date',
        'place',
        'offers',
        'short_description',
        'description',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // SyndicateOfferController), only the real fields above. NOT NULL with
    // no DB default, so they still need a value on insert.
    protected $attributes = [
        'publish_title' => '',
        'publish_main_image' => '',
        'publish_start_date' => '1970-01-01',
        'publish_end_date' => '1970-01-01',
        'publish_place' => '',
        'publish_offers' => '',
        'publish_short_description' => '',
        'publish_description' => '',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('offers/' . $value) : null;
    }

    // Old CMS had a second image slot for this ("new_image") that was never
    // actually finished/used anywhere on the site or API - kept here only
    // because it was explicitly requested to exist in the CRUD, not because
    // anything reads it.
    public function getNewImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('offers/' . $value) : null;
    }

    public function getPdfAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('offers-files/' . $value) : null;
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

    // Still used by the homepage teaser - safe to keep even though the new
    // CMS never populates publish_main_image, it just falls through to
    // main_image whenever that column is empty.
    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('offers', $raw['main_image'], $raw['publish_main_image'], $raw['publish_status']);
    }
}
