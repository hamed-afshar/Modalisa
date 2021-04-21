<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

class SubscriptionExist extends Exception
{
    public function report()
    {

    }

    public function render($request)
    {
        return response(['error' => new ErrorResource($request), 'message' => trans('translate.subscription_is_already_exist')]);
    }
}
