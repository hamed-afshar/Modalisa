<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

class ChangeHistoryNotAllowed extends Exception
{
    public function report()
    {

    }

    public function render($request)
    {
        return response(['error' => trans('translate.action_not_allowed'), 'message' => trans('translate.change_history_denied')]);
    }
}
