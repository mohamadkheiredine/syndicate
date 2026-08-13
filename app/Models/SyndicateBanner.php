<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateBanner extends Model
{
    protected $table = 'syndicate_banner';

    public $timestamps = false;

    protected $fillable = [
        'main_image',
        'status',
        'publish_status',
    ];

    // Legacy column, not used by the new CMS (see BannerController) - NOT NULL
    // with no DB default, so it needs a value on insert.
    protected $attributes = [
        'publish_main_image' => '',
    ];

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('banners/' . $value) : null;
    }
}
