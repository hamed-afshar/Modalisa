<?php

/**
 * this trait handles uploading images
 */

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function uploadOne(UploadedFile $uploadFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $file = $uploadFile->storeAs($folder, $name . '.' . $uploadFile->getClientOriginalExtension(), $disk);
        return $file;
    }

    public function deleteOne($disk = 'public', $imageName = null)
    {
        Storage::disk($disk)->delete($imageName);
    }

}
