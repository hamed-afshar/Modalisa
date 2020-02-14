<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model

{
    protected $fillable = [
        'subscriptionID', 'plan', 'cost_percentage'
    ];
    protected $primaryKey = 'subscriptionID';
}
