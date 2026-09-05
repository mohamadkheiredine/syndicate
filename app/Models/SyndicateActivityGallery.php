<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateActivityGallery extends Model
{
    protected $table = 'syndicate_gallery';

    protected $primaryKey = 'gallery_id';

    public $timestamps = false;

    protected $fillable = [
        'activities_id',
        'gallery_image',
    ];

    public function getGalleryImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('activity_gallery/' . $value) : null;
    }

    public function activity()
    {
        return $this->belongsTo(SyndicateActivity::class, 'activities_id');
    }
}
