<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndicateTermsCondition extends Model
{
    protected $table = 'syndicate_terms_conditions';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'publish_status',
    ];

    // Legacy shadow column - the new CMS never writes to this (see
    // TermsConditionsController), only the real field above. NOT NULL with
    // no DB default, so it still needs a value on insert.
    protected $attributes = [
        'publish_description' => '',
    ];

    // Old CMS ran rich-text through htmlspecialchars() before saving, so
    // existing rows have literally-escaped tags (e.g. "&lt;p&gt;") stored -
    // decode so the edit form's rich-text editor renders them instead of
    // showing the raw escaped text. A no-op on new, already-clean saves.
    public function getDescriptionAttribute($value)
    {
        return stripslashes(html_entity_decode($value ?? ''));
    }
}
