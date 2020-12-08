<?php

/**
 * this trait handles uploading images
 */

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait DeleteTrait
{
    public function deleteOne($filename)
    {
        File::delete(public_path('storage\images\\'. $filename));
    }
}
