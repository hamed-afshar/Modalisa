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
                <h2> {{$user->name}} </h2>
                <h2> {{$user->email}} </h2>
                <h2> {{$user->confirmed}} </h2>
                <h2> {{$user->access_level}} </h2>
                <h2> {{$user->last_login}} </h2>
                <h2> {{$user->lock}} </h2>
                <h2> {{$user->last_ip}} </h2>
                <h2> {{$user->language}} </h2>
                <h2> {{$user->tel}} </h2>
                <h2> {{$user->country}} </h2>
                <h2> {{$user->communication_media}} </h2>
            </li>
            @empty
            <li> Not any registerd user yet</li>
            @endforelse
        </ul>
    </body>
</html>

