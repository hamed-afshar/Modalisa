<!DOCTYPE html>
<html>
    <head>
        <title>

        </title>
    </head>
    <body>
        <h1> Roles </h1>
        <ul>
            @forelse($orders as $order)
                <li>
                    <a href = "{{ $order->path() }}"> {{ $order->orderID }} </a>
                </li>
            @empty
                <li> No Project Yet </li>
            @endforelse
        </ul>

    </body>
</html>
