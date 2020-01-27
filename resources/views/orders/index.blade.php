<!DOCTYPE html>
<html>
    <head>
        <title>
            
        </title>
    </head>
    <body>
        <h1> Modalisa </h1>
        <ul>
            @foreach($orders as $order)
                <li>{{ $order->orderID }}</li>
            @endforeach
        </ul>
        
    </body>
</html>