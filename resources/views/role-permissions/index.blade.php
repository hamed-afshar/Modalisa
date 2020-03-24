<!DOCTYPE html>
<html>
<head>
    <title>

    </title>
</head>
<body>
<h1> Modalisa </h1>
@forelse($rolePermissions as $rolePermission)
    <li>
        <h1> {{ $rolePermission->id }}</h1>
        <h1> {{ $rolePermission->user_id }}</h1>
        <h1> {{ $rolePermission->permission_id }} </h1>
    </li>
@empty
    <li> Not any permission assigned to role yet</li>
@endforelse
</body>
</html>
