<!DOCTYPE html>
<html  class="h-full bg-gray-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <!-- <script src="/socket.io/socket.io.js"></script> -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <!-- <script src="/socket.io/socket.io.js"></script> -->
        <!-- <script src="{{ mix('js/laravel-echo-setup.js') }}" type="text/javascript"></script> -->
    </head>
    <body class="font-sans antialiased h-full">
        @inertia

        @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv

        <x-support-bubble />
    </body>
    <script type="text/javascript">
        var i = 0;
        // window.Echo.channel('user-channel')
        //  .listen('UserEvent', (data) => {
        //     i++;
        //     console.log('test here');
        //     $("#notification").append('<div class="alert alert-success">'+i+'.'+data.title+'</div>');
        // });
    </script>
</html>
