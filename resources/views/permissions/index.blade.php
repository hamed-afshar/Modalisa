<!DOCTYPE html>
<html>
<head>
    <title>

    </title>
</head>
<body>
<h1> Modalisa </h1>
<ul>
    @forelse($permissions as $permission)
        <li> {{ $permission->id }}</li>
    @empty
        <li> No Permission Yet </li>
    @endforelse
</ul>

</body>
</html>
