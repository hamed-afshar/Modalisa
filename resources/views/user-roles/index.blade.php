<html>
<head>
<body>
<h1> Assigned Roles to users</h1>
@forelse($userRoles as $userRole)
    <li>
        <h1> {{ $userRole->id }} </h1>
        <h1> {{ $userRole->user_id }}</h1>
        <h1> {{ $userRole->role_id }}</h1>
    </li>
@empty
    <li> Not Any Roles Assignment Yet</li>
@endforelse
</body>
</head>
</html>

