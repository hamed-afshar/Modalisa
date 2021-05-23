<?php


namespace App\Helper;


use App\Product;
use Illuminate\Support\Facades\DB;

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
         * 0: Order Deleted             Status Table Record: 1
         * 1: Order Created             Status Table Record: 2
         * 2: Order Bought              Status Table Record: 3
         * 3: Order in office           Status Table Record: 4
         * 4: Order in kargo to iran    Status Table Record: 5
         * 5: Order in iran             Status Table Record: 6
         * 6: Order in kargo from iran  Status Table Record: 7
         * 7: Order returned            Status Table Record: 8
         * 8: Order refunded              Status Table Record: 9
         * 9: Order Edited              Status Table Record: 10
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
        $this->currentInDB = $current;
        $this->next = $next;

        //variable to holds the corresponding value for the current and next status in the matrix
        $this->currentInMatrix = $current - 1;
        $this->nextInMatrix = $next - 1;
    }


    /**
     * function to check validity of the status movement from current to the next.
     * @return bool
     */
    public function check(): bool
    {
        if ($this->statusMatrix[$this->currentInMatrix][$this->nextInMatrix] == 1) {
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
        $this->product->histories()->create([
            'status_id' => $this->next,
        ]);
    }
}
