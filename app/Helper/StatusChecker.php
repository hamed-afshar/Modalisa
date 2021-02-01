<?php


namespace App\Helper;


class StatusChecker
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
    public $statusMatrix = array(
        array(1, 1 ,0 ,0 ,0 ,0 ,0 ,0 ,0),
        array(1, 1 ,1 ,0 ,0 ,0 ,0 ,0 ,0),
        array(0, 0 ,1 ,1 ,0 ,0 ,0 ,0 ,0),
        array(0, 0 ,0 ,1 ,1 ,0 ,0 ,1 ,0),
        array(0, 0 ,0 ,0 ,1 ,1 ,0 ,0 ,0),
        array(0, 0 ,0 ,0 ,0 ,1 ,1 ,0 ,0),
        array(0, 0 ,0 ,1 ,0 ,0 ,1 ,0 ,0),
        array(0, 0 ,0 ,1 ,0 ,0 ,0 ,1 ,1),
        array(0, 0 ,0 ,0 ,0 ,0 ,0 ,0 ,0),
    );

    public function check($current, $next)
    {
        dd('class');
        if($this->statusMatrix[$current][$next] == 1) {
            dd('hamed');
            return true;
        }
    }
}
