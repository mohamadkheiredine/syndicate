<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait HasImageAttribute
{
    public function image(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value) {
                if (!$value) return null;

                $s3Path = strtolower(config('app.name')) . "/$value";

                return config('services.s3bucket.status') ?
                    Storage::disk('s3')->url($s3Path)
                    : asset("storage/$value");
            }
        );
    }
}
