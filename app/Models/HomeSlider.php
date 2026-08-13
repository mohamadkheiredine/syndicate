<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    protected $table = 'syndicate_sliders';

    public $timestamps = false;

    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('home-slider', $raw['main_image'], $raw['publish_main_image'], $raw['publish_status']);
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
