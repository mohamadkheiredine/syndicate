<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    /**
     * Store file
     *
     */
    static function storeFile($folder, $request_file, $title = null, $random = null)
    {
        $tmp_file = $request_file;

        if(isset($title) && $title)
            $tmp_file_name = strtolower(str_replace(" ", "-", $title));
        else
            $tmp_file_name = basename($tmp_file->getClientOriginalName(), '.'.$tmp_file->getClientOriginalExtension());

        $tmp_file_extension = $tmp_file->getClientOriginalExtension();

        if($random){
            $tmp_file_name = preg_replace('/[^A-Za-z0-9\-]/', '', $tmp_file_name);
            $original_name = $tmp_file_name . "-" . rand(0000, 9999) . "." . $tmp_file_extension;
        } else {
            $original_name = $tmp_file_name . "." . $tmp_file_extension;
        }

        // Random title path
        $original_name = Carbon::now()->timestamp . "-" . rand() . "." . $tmp_file_extension;

        if(Config::get('services.s3bucket.status')){
            Storage::disk('s3')->put(config('filesystems.disks.s3.bucket_name').'/'.$folder.'/'.$original_name, file_get_contents($request_file));
        } else {
            $tmp_file->storeAs('public/'.$folder, $original_name);
        }

        return $original_name;
    }

    /**
     * Delete file function
     *
     */
    static function deleteFile($folder, $row)
    {
        if(Config::get('services.s3bucket.status')){
            $path = Storage::disk('s3')->delete('machroub/'.$folder.'/'.$row->getAttributes()['image']);
        } else {
            $path = public_path().Storage::url($folder.'/'.$row->getAttributes()['image']);
        }

        if(File::exists($path)){
            File::delete($path);
        }
    }


    static function getImageFullUrl($image)
    {
        if(config('services.s3bucket.status')){
            return $image ? Storage::disk('s3')->url(config('filesystems.disks.s3.bucket_name')."/$image") : null;
        } else {
            // Files saved via storeFile() live on the 'public' disk (storage/app/public), which is only
            // web-reachable through the /storage symlink — Storage::url() adds that prefix.
            $path = $image ? Storage::url($image) : $image;
            return $image && config('app.env') != 'local' ? secure_asset($path) : asset($path);
        }
    }

    /**
     * Resolve which image to show for rows that carry both a live image and a
     * pending (publish_*) one awaiting approval - same rule the old site used
     * everywhere: show the pending image only once it's been approved.
     *
     */
    static function getDisplayImageUrl($folder, $mainImage, $publishImage = null, $publishStatus = null)
    {
        if($publishImage && (int) $publishStatus === 1){
            return self::getImageFullUrl($folder.'/'.$publishImage);
        }

        return $mainImage ? self::getImageFullUrl($folder.'/'.$mainImage) : null;
    }

    /**
     * Delete a file given its folder and filename directly - for models whose
     * image column isn't literally named "image" (deleteFile() assumes that).
     *
     */
    static function deleteFileByName($folder, $filename)
    {
        if(Config::get('services.s3bucket.status')){
            $path = Storage::disk('s3')->delete(config('filesystems.disks.s3.bucket_name').'/'.$folder.'/'.$filename);
        } else {
            $path = public_path().Storage::url($folder.'/'.$filename);
        }

        if(File::exists($path)){
            File::delete($path);
        }
    }

    /**
     * Delete file function
     *
     */
    static function deleteFileByPath($path)
    {
        if(Config::get('services.s3bucket.status')){
            $path = Storage::disk('s3')->delete(config('filesystems.disks.s3.bucket_name').'/'.$path);
        } else {
            $path = public_path().$path;
        }

        if(File::exists($path)){
            File::delete($path);
        }
    }
}
