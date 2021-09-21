<?php

/**
 * trait to handle history functions
 */

namespace App\Traits;

use App\Exceptions\ChangeHistoryNotAllowed;
use App\Helper\StatusManager;
use App\Product;
use App\Status;
use Illuminate\Support\Facades\DB;

trait HistoryTrait
{
    /**
     * function to get the latest status of the given product
     * @param Product $product
     * @return int
     */
    public function getStatus(Product $product): int
    {
        $latestHistory = DB::table('histories')
            ->where('product_id', '=', $product->id)
            ->orderBy('id', 'desc')->first();
        return $latestHistory->status_id;
    }

    /**
     * function to change the history from current status to the next status
     * @throws ChangeHistoryNotAllowed
     */
    public function storeHistory(Product $product, Status $status)
    {
        $currentStatus = $this->getStatus($product);
        $nextStatus = $status->id;
        $statusManager = new StatusManager($product, $currentStatus, $nextStatus);
        if($statusManager->check()) {
            $statusManager->changeHistory();
        } else {
            throw new ChangeHistoryNotAllowed();
        }
    }
}
