<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateFamily extends Model
{
    protected $table = 'syndicate_family';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'name',
        'main_image',
        'designation',
        'syndicate_year',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // SyndicateFamilyController), only the real fields above. NOT NULL with
    // no DB default, so they still need a value on insert.
    protected $attributes = [
        'publish_name' => '',
        'publish_main_image' => '',
        'publish_designation' => '',
        'publish_syndicate_year' => '',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('syndicate_family/' . $value) : null;
    }
}
