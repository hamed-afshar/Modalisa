<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

class ProductDeleteNotAllowed extends Exception
{
    public function report()
    {

    }

    public function render($request)
    {
        return response(['error' => trans('translate.action_not_allowed'), 'message' => trans('translate.product_delete_is_not_allowed')]);
    }
}
