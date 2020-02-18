<!DOCTYPE HTML>
<html>
    <head>
        <title>
            
        </title>
    </head>
    <body>
        <h1>User's Subscriptions</h1>
        <ul>
            @forelse($subscriptionList as $subscription)
            <li>
                <h2> {{ $subscription->id }}</h2>
                <h2> {{ $subscription->plan }} </h2>
                <h2> {{ $subscription->cost_percentage }}</h2>
            </li>
            @empty
            <li>Not subscription yet</li>
            @endforelse
        </ul>
    </body>
</html>

