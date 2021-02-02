<?php


namespace App\Helper;


class StatusManager
{

    /**
     * StatusManager constructor.
     * @param $product
     * @param $current
     * @param $next
     */
    public function __construct($product, $current, $next)
    {
        /**
         * this variable holds all possible status combination
         * 0: Order Deleted
         * 1: Order Created
         * 2: Order Bought
         * 3: Order in-office
         * 4: Order in-kargo-to-iran
         * 5: Order in-iran
         * 6: Order in-kargo-from-iran
         * 7: Order returned
         * 8: Order refund
         * 9: Order Edited
         */
        $this->statusMatrix = array(
            array(1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
            array(1, 1, 1, 0, 0, 0, 0, 0, 0, 1),
            array(0, 0, 1, 1, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 1, 1, 0, 0, 1, 0, 0),
            array(0, 0, 0, 0, 1, 1, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
            array(0, 0, 0, 1, 0, 0, 1, 0, 0, 0),
            array(0, 0, 0, 1, 0, 0, 0, 1, 1, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 1, 0, 0, 0, 0, 0, 0, 1)
        );
        $this->product = $product;
        $this->current = $current;
        $this->next = $next;
    }


    /**
     * function ro check validity of the status movement from current to the next.
     * @return bool
     */
    public function check(): bool
    {
        if($this->statusMatrix[$this->current][$this->next]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * function to create create history
     */
    public function changeHistory()
    {
        $this->product->histories()->create(['status_id' => $this->next]);
    }
}
