<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndicateAbout extends Model
{
    protected $table = 'syndicate_about';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'publish_status',
    ];

    // Legacy shadow column - the new CMS never writes to this (see
    // SyndicateAboutController), only the real field above. NOT NULL with
    // no DB default, so it still needs a value on insert.
    protected $attributes = [
        'publish_description' => '',
    ];

    // Old CMS ran rich-text through htmlspecialchars() before saving, so the
    // existing row has literally-escaped tags (e.g. "&lt;p&gt;") stored -
    // decode so the edit form's rich-text editor renders it instead of
    // showing the raw escaped text. A no-op on new, already-clean saves.
    public function getDescriptionAttribute($value)
    {
        return stripslashes(html_entity_decode($value ?? ''));
    }

    // Used by the public About page - shows the pending (publish_description)
    // text once it's been approved, otherwise falls back to description.
    // Matches the old site's exact fallback rule.
    public function getDisplayDescriptionAttribute()
    {
        $raw = $this->getAttributes();

        if($raw['publish_description'] != '' && (int) $raw['publish_status'] === 1){
            return stripslashes(html_entity_decode($raw['publish_description']));
        }

        return $this->description;
    }
}
