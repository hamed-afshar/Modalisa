<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Role;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Role' => 'App\Policies\RolePolicy',
        'App\Permission' => 'App\Policies\PermissionPolicy',
        'App\Subscription' => 'App\Policies\SubscriptionPolicy',
        'App\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Status' => 'App\Policies\StatusPolicy',
        'App\Note' => 'App\Policies\NotePolicy',
        'App\Image' => 'App\Policies\ImagePolicy',
        'App\Admin' => 'App\Policies\AdminPolicy',
        'App\kargo' => 'App\Policies\KargoPolicy',
        'App\Order' => 'App\Policies\OrderPolicy',
        'App\Cost' => 'App\Policies\CostPolicy',
        'App\History' => 'App\Policies\HistoryPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
