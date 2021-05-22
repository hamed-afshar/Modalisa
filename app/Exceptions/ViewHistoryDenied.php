<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Exception;

class ViewHistoryDenied extends Exception
{
    public function report()
    {

    }

    public function render($request)
    {
        return response(['error' => trans('translate.action_not_allowed'), 'message' => trans('translate.view_history_denied')]);
    }
}
