<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SyndicateLogo extends Model
{
    protected $table = 'syndicate_logo';

    public $timestamps = false;

    protected $fillable = [
        'logo'
    ];

    protected $attributes = [
        'big_logo' => ''
    ];

    public function getLogoAttribute($value){
        return $value ? FilesHelper::getImageFullUrl('logo/' . $value) : null;
    }
}
