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
        $tmp_file_extension = $request_file->getClientOriginalExtension();

        // File is always stored under a random, collision-proof name
        $original_name = Carbon::now()->timestamp . "-" . rand() . "." . $tmp_file_extension;

        if (Config::get('services.s3bucket.status')) {
            $key = config('filesystems.disks.s3.bucket_name') . '/' . $folder . '/' . $original_name;

            try {
                $uploaded = Storage::disk('s3')->put($key, file_get_contents($request_file->getRealPath()));
            } catch (\Throwable $e) {
                // Surface the real underlying reason instead of guessing
                throw new \RuntimeException(
                    "S3 upload failed for '{$key}' on bucket '".config('filesystems.disks.s3.bucket')."': ".$e->getMessage()
                );
            }

            if ($uploaded === false) {
                throw new \RuntimeException(
                    "S3 upload failed for '{$key}' on bucket '".config('filesystems.disks.s3.bucket')."'. "
                    ."Likely a PHP SSL/CA-bundle issue (cURL error 60) or invalid AWS credentials / missing s3:PutObject."
                );
            }
        } else {
            $request_file->storeAs('public/'.$folder, $original_name);
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


    static function getDisplayImageUrl($folder, $mainImage, $publishImage = null, $publishStatus = null, $publishFolder = null)
    {
        if($publishImage && (int) $publishStatus === 1){
            return self::getImageFullUrl(($publishFolder ?? $folder).'/'.$publishImage);
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
