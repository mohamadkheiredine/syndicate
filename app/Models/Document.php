<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'title',
        'file',
    ];

    public function getFileUrlAttribute()
    {
        return $this->file ? FilesHelper::getImageFullUrl('documents/' . $this->file) : null;
    }
}
