<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    protected $table = 'syndicate_sliders';

    public $timestamps = false;

    protected $fillable = [
        'main_image',
        'mobile_image',
        'title',
        'subtitle',
        'text',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // HomeSliderController), only the real fields above. NOT NULL with no
    // DB default, so they still need a value on insert. Note: this table
    // has no admin_id column at all, unlike every other module.
    protected $attributes = [
        'publish_title' => '',
        'publish_subtitle' => '',
        'publish_text' => '',
    ];

    // Standard accessors for the CMS edit form's raw stored image (as
    // opposed to display_image/display_mobile_image below, which resolve
    // the published-vs-pending choice for the public site). Safe to define
    // here because all the display_* accessors read via getAttributes(),
    // bypassing these.
    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('home-slider/' . $value) : null;
    }

    public function getMobileImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('home-slider/' . $value) : null;
    }

    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('home-slider', $raw['main_image'], $raw['publish_main_image'], $raw['publish_status'], 'publish_home_slider');
    }

    public function getDisplayMobileImageAttribute()
    {
        $raw = $this->getAttributes();

        return $raw['mobile_image'] ? FilesHelper::getImageFullUrl('home-slider/'.$raw['mobile_image']) : $this->display_image;
    }

    public function getDisplayTitleAttribute()
    {
        return $this->resolvePublished('title');
    }

    public function getDisplaySubtitleAttribute()
    {
        return $this->resolvePublished('subtitle');
    }

    public function getDisplayTextAttribute()
    {
        return $this->resolvePublished('text');
    }

    private function resolvePublished($field)
    {
        $raw = $this->getAttributes();

        if($raw['publish_'.$field] && (int) $raw['publish_status'] === 1){
            return $raw['publish_'.$field];
        }

        return $raw[$field];
    }
}
