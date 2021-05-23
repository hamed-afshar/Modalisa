<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ChangeHistoryNotAllowed;
use App\Exceptions\ViewHistoryDenied;
use App\Helper\StatusManager;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Product;
use App\Status;
use App\Traits\HistoryTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    use HistoryTrait;
    /**
     * index histories
     * users should have see-histories permission to be allowed
     * returns all histories for the given product
     * users can only index their own records
     * @param Product $product
     * @return mixed
     * @throws AuthorizationException|ViewHistoryDenied
     */
    public function index(Product $product)
    {
        $this->authorize('viewAny', History::class);
        $owner = $product->order->user;
        //if products belongs to the current authenticated user then return all related histories, otherwise returns null
        if($owner->id == Auth::user()->id) {
            $histories = $product->histories()->with('status')->get();
            return response(['histories' => HistoryResource::collection($histories), 'message'=>trans('translate.retrieved')], 200);
        } else {
            throw new ViewHistoryDenied();
        }
    }

    /**
     * create history
     * BuyerAdmin and users with privilege permissions are allowed
     * @param Request $request
     * @param Product $product
     * @param Status $status
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException|ChangeHistoryNotAllowed
     */
    public function store(Request $request, Product $product, Status $status)
    {
        $this->authorize('create', History::class);
        $currentStatus = $this->getStatus($product);
        $nextStatus = $status->id;
        $statusManager = new StatusManager($product, $currentStatus, $nextStatus);
        if($statusManager->check()) {
            $statusManager->changeHistory();
        } else {
            throw new ChangeHistoryNotAllowed();
        }
        return response(['message' => trans('translate.history_changed')], 200);
    }

    /**
     * delete history
     * BuyerAdmin and users with privilege permissions are allowed
     * @param History $history
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(History $history)
    {
        $this->authorize('delete', $history);
        $history->delete();
        return response(['messaage' => trans('translate.history_deleted')], 200);
    }
}
