<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

/**
 * Class to throw error if role is already exist in db
 * @package App\Exceptions
 */
class RoleExists extends Exception
{
    public function report()
    {

    }

    public function render($request)
    {
        return response(['error' => new ErrorResource($request), 'message' => trans('translate.role_is_already_exist')]);
    }
}
