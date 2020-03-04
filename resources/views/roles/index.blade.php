<!DOCTYPE html>
<html>
<head>
    <title>

    </title>
</head>
<body>
<h1> Modalisa </h1>
<ul>
    @forelse($roles as $role)
        <li> {{ $role->id }}</li>
    @empty
        <li> No roles Yet </li>
    @endforelse
</ul>

</body>
</html>
