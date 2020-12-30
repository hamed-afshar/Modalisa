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
    /**
     * @param UploadedFile $uploadFile
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    public function uploadOne(UploadedFile $uploadFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $file = $uploadFile->storeAs($folder, $name . '.' . $uploadFile->getClientOriginalExtension(), $disk);
        return $file;
    }

    /**
     * receives an array of image names and delete all of them one by one
     * @param string $disk
     * @param null $imageNameArray
     */
    public function deleteOne($disk = 'public', $imageNameArray = null)
    {
        foreach($imageNameArray as $imageName)
        {
            Storage::disk($disk)->delete($imageName);
        }
    }

}
