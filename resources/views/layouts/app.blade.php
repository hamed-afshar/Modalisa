<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Modalisa') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
       <nav class="flex justify-between bg-gray-200 px-12 py-6">
           <div>
               <h1 style="font-size: 40px" class="text-gray-900 font-extrabold"> Modalisa </h1>
           </div>
           <div class="lg:flex">
               <a href = "#" class="inline-block bg-blue-400 p-6"> Sign In</a>
           </div>
       </nav>


    </div>
</body>
</html>
