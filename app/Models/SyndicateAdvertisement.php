<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndicateAdvertisement extends Model
{
    protected $table = 'syndicate_advertisement';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'advertise_type',
        'title',
        'dimension',
        'what_type',
        'price',
        'duration',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // SyndicateAdvertisementController), only the real fields above. NOT
    // NULL with no DB default, so they still need a value on insert.
    // "location" and "url" are dead columns from the old CMS (location's
    // select was commented out, url was never referenced anywhere) so they
    // are not fillable and default to empty here too.
    protected $attributes = [
        'publish_advertise_type' => '',
        'publish_title' => '',
        'publish_dimension' => '',
        'publish_what_type' => '',
        'publish_price' => '',
        'publish_duration' => '',
        'location' => '0',
        'url' => '',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
