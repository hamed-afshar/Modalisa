<!DOCTYPE HTML>
<html>
    <head>
        <title>

        </title>
    </head>
    <body>
        <h1>User's Subscriptions</h1>
        <ul>
            @forelse($subscriptions as $subscription)
            <li>
                <h2> {{ $subscription->id }}</h2>
            </li>
            @empty
            <li>Not subscription yet</li>
            @endforelse
        </ul>
    </body>
</html>

