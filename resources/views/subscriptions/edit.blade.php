<!DOCTYPE HTML>
<html>
    <head>
        <title>
            
        </title>
    </head>
    <body>
        <h1>User's Subscriptions</h1>
        <ul>
            @forelse($userSubscription as $subscription)
            <li>
                <h2> {{ $subscription->user_id }}</h2>
                <h2> {{ $subscription->subscription_id }} </h2>
            </li>
            @empty
            <li>Not subscription yet</li>
            @endforelse
        </ul>
    </body>
</html>

