<?php


namespace App\Helper;


class StatusChecker
{

    /**
     * StatusChecker constructor.
     * @param $current
     * @param $next
     */
    public function __construct($current, $next)
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
         */
        $this->statusMatrix = array(
            array(1, 1, 0, 0, 0, 0, 0, 0, 0),
            array(1, 1, 1, 0, 0, 0, 0, 0, 0),
            array(0, 0, 1, 1, 0, 0, 0, 0, 0),
            array(0, 0, 0, 1, 1, 0, 0, 1, 0),
            array(0, 0, 0, 0, 1, 1, 0, 0, 0),
            array(0, 0, 0, 0, 0, 1, 1, 0, 0),
            array(0, 0, 0, 1, 0, 0, 1, 0, 0),
            array(0, 0, 0, 1, 0, 0, 0, 1, 1),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
        );
        $this->current = $current;
        $this->next = $next;
    }


    public function check()
    {
        if($this->statusMatrix[$this->current][$this->next]) {
            dump('yes');
        } else {
            dump('no');
        }
    }
}
