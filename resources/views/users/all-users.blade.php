<!DOCTYPE HTML>
<html>
    <head>
        <title>
            
        </title>
    </head>
    <body>
        <h1> All Users </h1>
        <ul>
            @forelse($allUsers as $user)
            <li>
                <h2> {{$user->name}}</h2>
            </li>
            @empty
            <li> Not any registerd user yet</li>
            @endforelse
        </ul>
    </body>
</html>

