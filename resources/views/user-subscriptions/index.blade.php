<html>
<head>
<body>
<h1> Assigned subscriptions to users</h1>
@forelse($userSubscriptions as $userSubscription)
    <li>
        <h1>
            {{ $userSubscription->user_id }}
            {{ $userSubscription->subscription_id }}
        </h1>
    </li>
@empty
    <li> Not Any Assignment</li>
@endforelse
</body>
</head>
</html>
