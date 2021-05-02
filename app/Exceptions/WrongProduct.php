<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

class WrongProduct extends Exception
{
    public function report()
    {

    }
    public function render($request)
    {
        return response(['error' => new ErrorResource($request), 'message' => trans('translate.wrong_kargo_add')]);
    }
}
