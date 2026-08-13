<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateOthersAdvertisement extends Model
{
    protected $table = 'syndicate_others_advertisement';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'main_image',
        'position',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // SyndicateOthersAdvertisementController), only the real fields above.
    // NOT NULL with no DB default, so they still need a value on insert.
    // "url" is a dead column from the old CMS - never written by any admin
    // form nor read by any public page - so it is not fillable either.
    protected $attributes = [
        'publish_main_image' => '',
        'publish_position' => '',
        'url' => '',
    ];

    public const POSITIONS = [
        1 => 'Right Up',
        2 => 'Right Down',
        3 => 'Home',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('syndicate-others-advertisement/' . $value) : null;
    }

    public function getPositionLabelAttribute()
    {
        return self::POSITIONS[(int) $this->getAttributes()['position']] ?? '';
    }
}
